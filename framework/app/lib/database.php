<?php

/*
 *  DBへのアクセスを行うクラス
 *  PDOをラップして使いやすくしたクラス
 */
class Database {

    // DBハンドラ
    public $conn = null;

    // SQL
    private $sql = "";

    // PDOStatement
    private $stmt = null;

    // SQLのLIMIT句
    private $limit = "";

    // SQLのGROUP BY句
    private $groupBy = "";

    // SQLのORDER BY句
    private $orderBy = "";
   
    // トランザクションが開始されているかのフラグ
    Private $inTransaction = False; 

    // インサートした後のラストID
    private $lastId = 0;

    // SQLにBindしたデータ
    private $bindData = array();

    // Where句が存在するかフラグ
    private $existWhere = false;

    // fetchが行われたかのフラグ
    private $fetchFlag = false;

    // SELECTしたデータの行数
    private $count = 0;

    // このクラスのインスタンス
    private static $_instance = null;
    
    // シングルトンクラスなので、private
    private function __construct(){
    }

    // インスタンス生成メソッド
    public static function getInstance(){
        if(is_null(self::$_instance)){
            self::$_instance = new self;
        }
        return self::$_instance;
    }
 
    // DBハンドラの生成
    public function open($dsn, $user, $pass){
        if($this->conn == null){
            try{
                $this->conn = new PDO($dsn, $user, $pass);
                $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                exit('DB failed connection: ' . $e->getMessage());
            }
        }
    }

    // DBハンドラの削除
    public function close(){
        $this->conn = null;    
    }

    // SQLをセットする
    public function setSql($sqlQuery){
        $this->clear();
        $this->sql = $sqlQuery;
        return $this;
    }

    // SQLを取得する
    public function getSql(){
        return $this->sql;
    }

    // バインドしたデータを取得
    public function getBindData(){
        return $this->bindData;
    }

    // SQL分を実行するメソッド（内部でトランザクションをはる）
    private function execute($bindData = array()){
        $this->transaction();
        $is_success = false;
        try {
            $this->stmt = $this->conn->prepare($this->sql);
            $is_success = $this->stmt->execute($bindData);
            $this->lastId = $this->conn->lastInsertId();
            $this->effectNum = $this->stmt->rowCount();
            $this->commit();
        } catch (PDOException $e) {
            $this->rollBack();
            //print_r("error");
            print_r($e->getMessage());
        }

        return $is_success;
    }

    // 全件取得
    public function fetchAll(){
        $this->fetchFlag = true;
        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->count = count($data);
        return $data;
    }

    // 一件のみ取得
    public function fetchRow(){
        $this->fetchFlag = true;
        $data = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->count = count($data);
        if(empty($data)) return array();
        return $data[0];
    }

    // 取得件数を取得
    public function fetchNum(){
        if(!$this->fetchFlag) {
            $this->fetchAll();
        }
        
        return $this->count;
    }

    // 更新系クエリで影響を与えた行数を返す
    public function rowCount(){
        return $this->effectNum;
    }

    // 最後に挿入されたIDを返す
    public function lastInsertId(){
        return $this->lastId;
    }

    // 内部のデータをリセットする
    public function clear(){
        $this->sql = "";
        $this->limit = "";
        $this->groupBy = "";
        $this->orderBy = "";
        $this->stmt = null;
        $this->lastId = 0;
        $this->inTransaction = false;
        $this->bindData = array();
        $this->existWhere = false;
        $this->fetchFlag = false;
    }

    // インサート文を生成するメソッド
    public function insert($tableName, array $insertData){
        $this->clear();
        $this->sql = "INSERT INTO " . $tableName;
        $column = array();
        $data = array();
        $placeholder = array();
        foreach($insertData as $k => $v){
            $column[] = $k;
            $data[] = $v;
            $placeholder[] = "?";
        }
        $this->sql .= " (" . implode(",", $column) . ") VALUES (" . implode(",", $placeholder) . ")";
        $this->bindData = $data;
        return $this;
    }

    // 構築したクエリを実行するメソッド
    public function run($data = array()){
        if(!empty($data)) $this->bindData = $data;
        $this->sql .= $this->groupBy . $this->orderBy . $this->limit; 
        $is_success = $this->execute($this->bindData);
        if($is_success) return $this;
        else return null;
    }

    // UPDATE文を生成するメソッド
    public function update($tableName, array $updateData){
        $this->clear();
        $this->sql = "UPDATE " . $tableName . " SET ";
        $column = array();
        $data = array();
        $placeholder = array();
        foreach($updateData as $k => $v){
            $column[] = $k . "=?";
            $data[] = $v;
        }
        $this->sql .= implode(',', $column);
        $this->bindData = $data;
        return $this;
    }

    // DELETE文を生成するメソッド
    public function delete($tableName){
        $this->clear();
        $this->sql = "DELETE FROM " . $tableName;
        return $this;
    }

    // SELECT文を生成するメソッド
    public function select($tableName, $selectData = array("*")){
        $this->clear();
        $this->sql = "SELECT ";
        if (is_array($selectData))
            $this->sql .= implode(',', $selectData);
        else
            $this->sql .= $selectData;
        $this->sql .= " FROM " . $tableName;
        return $this;
    }

    // WHERE句を生成するメソッド
    public function where($whereQuery, $data = ""){
        $prefix = "";
        if($this->existWhere == false){
            $prefix = " WHERE ";
            $this->existWhere = true;
        }
        else {
            $prefix .= " AND ";
        }
        $this->sql .= $prefix;
        $this->sql .= $whereQuery;
        if($data != ""){
            if(is_array($data)){
                foreach($data as $d){
                    $this->bindData[] = $d;
                }
            }
            else{
                $this->bindData[] = $data;
            }
        }
        return $this;
    }

    // LIMIT句を生成するメソッド
    public function limit($limit, $offset=""){
        $prefix = "";
        if($offset != "") $prefix = $offset . ","; 
        $this->limit = " LIMIT " . $prefix . $limit;
        return $this;
    }

    // GROUP BY 句を生成するメソッド
    public function groupBy($group){
        if(is_array($group))
            $this->groupBy = " GROUP BY " . implode(',', $group);
        else
            $this->groupBy = " GROUP BY " . $group;

        return $this;
    }

    // ORDER BY句を生成するメソッド
    public function orderBy($query){
        $prefix = "";
        if($this->orderBy == "") $prefix = " ORDER BY ";
        else $prefix = ",";
        $this->orderBy .= $prefix . $query;
        return $this;
    }

    // トランザクションを開始する処理（２重で開始されない様に拡張）
    public function transaction(){
       if(!$this->inTransaction){
           $this->conn->beginTransaction();
           $this->inTransaction = true;
       }
    }

    // トランザクションをコミットするメソッド（トランザクションが開始されていない時は実行しない）
    public function commit(){
        if($this->inTransaction) $this->conn->commit();
        $this->inTransaction = false;
    }
 
    // トランザクションをロールバックするメソッド（トランザクションが開始されていない時は実行しない）
    public function rollBack(){
        if($this->inTransaction) $this->conn->rollBack();
        $this->inTransaction = false;
    }

    
}


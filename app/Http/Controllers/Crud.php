<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Crud extends Controller
{
    // TODO: Get data
    public static function getData($table_name , array $data = ['*']){
        return DB::table($table_name)->select($data)->get();
    }

    // TODO: Get data Where
    public static function getDataWhere($table_name , $key, $operator = '=' , $value, array $data = ['*'] , $orderParam = 'id', $ordervalue= 'asc'){
        return DB::table($table_name)->where($key ,$operator,$value)->select($data)->get();
    }
    //TODO: Get data where id
    public static function getDataWhereId($table_name, array $data = ['*'] , $id = null ,$operator = '='){
        return DB::table($table_name)->select($data)->where('id' , $operator , $id)->Where('deleted_at' , null)->get();
    }

    //TODO: Get data with relation
    public static function getDataWithRelation($model, array $relation ,array $data = ['*'] ,$orderParam = 'id', $ordervalue= 'asc'){
        return $model::with($relation)->select($data)->get(); // ->find(id)
    }

    //TODO: Get data with relation
    public static function getDataWithRelationWhere($model, array $relation ,$key, $operator , $value,array $data = ['*'] ,$orderParam = 'id', $ordervalue= 'asc'){
        return $model::with($relation)->where($key , $operator ,$value)->orderBy($orderParam ,$ordervalue)->select($data)->get(); // ->find(id)
    }

    //TODO: Get data with relation
    public static function paginateDataWithRelation($model, array $relation ,$key, $operator , $value,array $data = ['*']){
        return $model::with($relation)->where($key , $operator ,$value)->Where('deleted_at' , null)->select($data)->paginate(3); // ->find(id)
    }
    //TODO: Get data with relation Where Id
    public static function getDataWithRelationWhereId($model, array $relation ,array $data = ['*']  , $id ,$orderParam = 'id', $ordervalue= 'asc'){
        return $model::with($relation)->select($data)->orderBy($orderParam,$ordervalue)->where('id' , $id)->where('deleted_at' , null)->get(); // ->find(id)
    }
    // TODO :Find
    public static function find($table_name , $id){
        return DB::table($table_name)->find($id);
    }

    // TODO: Create
    public static function store($table_name , array $data){
        return DB::table($table_name)->insert($data);
    }

    // TODO:update
    public static function update($model , $id , array $data = ['*']){
        return $model::where('uuid' ,$id)->update($data);
    }

    // TODO:update
    public static function multiUpdate($model , $ids , array $data){
        return $model::whereIn('uuid' ,$ids)->update($data);
    }

    // TODO:delete
    public static function deleteWhere($model , $key,$operator,$value){
        return $model::where($key ,$operator = '=',$value)->delete();
    }

    // TODO:soft delete
    public static function softdeletebyuuid($model , $ids , array $data){
        return $model::whereIn('uuid' ,$ids)->update($data);
    }
}

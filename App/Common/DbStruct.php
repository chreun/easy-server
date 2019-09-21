<?php


namespace App\Common;

use App\Service\BaseService;
use EasySwoole\Mysqli\DDLBuilder\Blueprints\TableBlueprint;
use \EasySwoole\Mysqli\DDLBuilder\DDLBuilder;
use \EasySwoole\Mysqli\DDLBuilder\Enum\{Character, Engines};


//$blueprint->colInt('test',10);
//$blueprint->colBigInt('test',10);
//$blueprint->colTinyInt('test',10);
//$blueprint->colSmallInt('test',10);
//$blueprint->colMediumInt('test');
//$blueprint->colFloat('test');
//$blueprint->colDouble('test');
//$blueprint->colDecimal('test');
//$blueprint->colDate('test');
//$blueprint->colYear('test');
//$blueprint->colTime('test');
//$blueprint->colDateTime('test');
//$blueprint->colTimestamp('test');
//$blueprint->colChar('test');
//$blueprint->colVarChar('test');
//$blueprint->colText('test');
//$blueprint->colTinyText('test');
//$blueprint->colLongText('test');
//$blueprint->colMediumText('test');
//$blueprint->colBlob('test');
//$blueprint->colLongBlob('test');
//$blueprint->colTinyBlob('test');
//$blueprint->colMediumBlob('test');


//$blueprint->indexFullText('indexName',['columnName']);
//$blueprint->indexNormal('indeName',['columnName']);
//$blueprint->indexPrimary('indeName',['columnName']);
//$blueprint->indexUnique('indeName',['columnName']);


// create database app default charset utf8 collate utf8_general_ci;

class DbStruct
{

    public static function exec(){
        $sql = self::user();

        try{
            BaseService::db()->rawQuery($sql);
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    public static function user(){
        $result = DDLBuilder::table('user', function (TableBlueprint $blueprint) {
            //这里使用$blueprint配置表
            $blueprint->setTableCharset(Character::UTF8_GENERAL_CI);//设置编码
            $blueprint->setTableEngine(Engines::INNODB);//设置引擎
            $blueprint->colInt('id', 11)->setIsUnsigned()->setIsPrimaryKey()->setIsAutoIncrement();
            $blueprint->colVarChar('username', 20)->setDefaultValue('');
            $blueprint->colChar('mobile', 11)->setDefaultValue('');
            $blueprint->colVarChar('portrait', 80)->setDefaultValue('');
            $blueprint->colChar('password', 60)->setDefaultValue('');
            $blueprint->colVarChar('openid', 32)->setDefaultValue('');
            $blueprint->colTinyInt('user_type')->setDefaultValue(0);
            $blueprint->colTimestamp('create_at')->setDefaultValue('now()');
            $blueprint->colTimestamp('last_login')->setDefaultValue('now()');
        });
        return $result;
    }


}
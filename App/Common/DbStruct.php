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


    public static function project(){
        $result = DDLBuilder::table('project', function (TableBlueprint $blueprint) {
            //这里使用$blueprint配置表
            $blueprint->setTableCharset(Character::UTF8_GENERAL_CI);//设置编码
            $blueprint->setTableEngine(Engines::INNODB);//设置引擎
            $blueprint->colInt('id', 11)->setIsUnsigned()->setIsPrimaryKey()->setIsAutoIncrement();
            $blueprint->colVarChar('username', 20)->setDefaultValue('');
            $blueprint->colVarChar('portrait', 100)->setDefaultValue('');
            $blueprint->colVarChar('title', 60)->setDefaultValue('');
            $blueprint->colVarChar('introduction', 255)->setDefaultValue('');
            $blueprint->colVarChar('image_lists', 255)->setDefaultValue('');
            $blueprint->colVarChar('patient', 10)->setDefaultValue('');
            $blueprint->colVarChar('sickness', 20)->setDefaultValue('');
            $blueprint->colVarChar('payee', 10)->setDefaultValue('');

            $blueprint->colMediumInt('annual_income', 4)->setDefaultValue(0);
            $blueprint->colInt('financial_assets', 11)->setDefaultValue(0);
            $blueprint->colTinyInt('house_count', 4)->setDefaultValue(0);
            $blueprint->colMediumInt('house_value', 4)->setDefaultValue(0);
            $blueprint->colInt('total_amount', 11)->setDefaultValue(0);

            $blueprint->colTimestamp('create_at')->setDefaultValue('now()');
        });
        return $result;
    }


    public static function prove(){
        $result = DDLBuilder::table('project_prove', function (TableBlueprint $blueprint) {
            //这里使用$blueprint配置表
            $blueprint->setTableCharset(Character::UTF8_GENERAL_CI);//设置编码
            $blueprint->setTableEngine(Engines::INNODB);//设置引擎
            $blueprint->colInt('id', 11)->setIsUnsigned()->setIsPrimaryKey()->setIsAutoIncrement();
            $blueprint->colInt('project_id', 11)->setDefaultValue(0);
            $blueprint->colVarChar('portrait', 11)->setDefaultValue('');
            $blueprint->colVarChar('name', 10)->setDefaultValue('');
            $blueprint->colVarChar('relation', 10)->setDefaultValue('');
            $blueprint->colVarChar('introduce', 10)->setDefaultValue('');
            $blueprint->colTimestamp('create_at')->setDefaultValue('now()');
        });
        return $result;
    }


    public static function dynamic(){
        $result = DDLBuilder::table('project_dynamic', function (TableBlueprint $blueprint) {
            //这里使用$blueprint配置表
            $blueprint->setTableCharset(Character::UTF8_GENERAL_CI);//设置编码
            $blueprint->setTableEngine(Engines::INNODB);//设置引擎
            $blueprint->colInt('id', 11)->setIsUnsigned()->setIsPrimaryKey()->setIsAutoIncrement();
            $blueprint->colInt('project_id', 11)->setDefaultValue(0);
            $blueprint->colVarChar('name', 10)->setDefaultValue('');
            $blueprint->colVarChar('portrait', 100)->setDefaultValue('');
            $blueprint->colVarChar('title', 50)->setDefaultValue('');
            $blueprint->colVarChar('image_lists', 10)->setDefaultValue('');
            $blueprint->colTimestamp('create_at')->setDefaultValue('now()');
        });
        return $result;
    }

    public static function order(){
        $result = DDLBuilder::table('order', function (TableBlueprint $blueprint) {
            //这里使用$blueprint配置表
            $blueprint->setTableCharset(Character::UTF8_GENERAL_CI);//设置编码
            $blueprint->setTableEngine(Engines::INNODB);//设置引擎
            $blueprint->colInt('id', 11)->setIsUnsigned()->setIsPrimaryKey()->setIsAutoIncrement();
            $blueprint->colInt('project_id', 11)->setDefaultValue(0);
            $blueprint->colInt('user_id', 11)->setDefaultValue(0);
            $blueprint->colVarChar('openid', 32)->setDefaultValue('');
            $blueprint->colDecimal('price', 8, 2)->setDefaultValue('0.0');
            $blueprint->colTimestamp('create_at')->setDefaultValue('now()');
        });
        return $result;
    }

}
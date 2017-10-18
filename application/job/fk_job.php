<?php
/**
 * Created by PhpStorm.
 * User: zhangjianbo
 * Date: 2017/10/17
 * Time: 下午5:20
 */

class Fk_job
{
    public function perform(){
        $model = new Base_model();
        $model->insert('test',
            ['name'=>$this->args['name'],
              'age'=>$this->args['age']
                ]);
    }
}
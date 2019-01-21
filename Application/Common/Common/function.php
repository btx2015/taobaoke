<?php
/**
 * Created by PhpStorm.
 * User: leasin
 * Date: 2019/1/17
 * Time: 14:25
 */

/**
 * 参数验证方法
 * @param array $paramRules
 * [
 *    array [
 *              array | string 验证规则1，
 *              验证规则2，
 *              ...
 *          ],
 *    bool 是否必须 默认 false，
 *    bool 是否可以传入 '' 或者 0  默认 true，
 *    string|array [表达式，替换的键名] | 表达式  是否转换为查询条件数组
 * ]
 * 例：
 * [
 *      'username'  =>  [[],false,true,'like'],
 *      'phone'     =>  [],
 *      'status'    =>  [['in'=>[1,2],'num'],false,true,['eq','state']],
 *      'from_time' =>  [['time'],false,true,['egt','created_at']],
 *      'to_time'   =>  [['time'],false,true,['elt','created_at']],
 * ]
 * @return array|bool
 */
function validate(array $paramRules = []){
    $params = [];
    $flag = true;//重复参数标识
    foreach($paramRules as $paramName => $rules){
        $paramValue = I('post.'.$paramName);
        if(!$paramValue){
            //是否可以为空或者为0
            if(isset($rules[2]) && !$rules[2])
                return false;
            //是否为必须参数
            if(isset($rules[1]) && $rules[1]){
                return false;
            }else{
                continue;
            }
        }
        //参数验证和处理
        if(isset($rules[0])){
            //验证规则必须是数组
            if(!is_array($rules[0]))
                showError(10007);
            //遍历规则
            foreach($rules[0] as $type => $filters){
                if(is_numeric($type))
                    $type = $filters;
                switch($type){
                    case 'in':
                        if(!is_array($filters))
                            showError(10007);
                        if(!in_array($paramValue,$filters))
                            return false;
                        break;
                    case 'not in':
                        if(!is_array($filters))
                            showError(10007);
                        if(in_array($paramValue,$filters))
                            return false;
                        break;
                    case 'num':
                        if(!is_numeric($paramValue))
                            return false;
                        break;
                    case 'phone':
                        if(!preg_match('/^1[0-9]{10}$/',$paramValue))
                            return false;
                        break;
                    case 'email':
                        $email = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
                        if(!preg_match($email,$paramValue))
                            return false;
                        break;
                    case 'range':
                        break;
                    case 'not range':
                        break;
                    case 'time':
                        $paramValue = strtotime($paramValue);
                        break;
                    default:
                        return false;
                        break;
                }
            }
        }
        //是否需要组装为查询条件数组
        if(isset($rules[3]) && $rules[3]){
            //是否切换键名
            if(is_array($rules[3])){
                //是否配置表达式 和 替换的键名
                if(!isset($rules[3][0]) || !isset($rules[3][1]))
                    showError(10007);
                //重复键名组装为时间范围
                if(isset($params[$rules[3][1]]) && $flag){
                    $flag = false;
                    $params[$rules[3][1]] = [
                        'between',
                        [
                            $params[$rules[3][1]][1],
                            $paramValue
                        ]
                    ];
                }else
                    $params[$rules[3][1]] = [$rules[3][0],$paramValue];
            }else
                $params[$paramName] = [$rules[3],$paramValue];
        }else
            $params[$paramName] = $paramValue;
    }
    return $params;
}

function returnResult($data = []){
    $result = [
        'code' => 1,
        'data' => $data
    ];
    ajaxReturn($result);
}

function showError($errorCode, $errorMsg = ''){
    if(!$errorMsg){
        $errorArray = C('ERROR_MESSAGE');
        if(isset($errorArray[$errorCode]))
            $errorMsg = $errorArray[$errorCode];
        else
            $errorMsg = '错误';
    }
    ajaxReturn([
        'code' => $errorCode,
        'msg' => $errorMsg
    ]);
}

/**
 * Ajax方式返回数据到客户端
 * @access protected
 * @param mixed $data 要返回的数据
 * @param String $type AJAX返回数据格式
 * @param int $json_option 传递给json_encode的option参数
 * @return void
 */
function ajaxReturn($data,$type='',$json_option=0){
    if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
    switch (strtoupper($type)){
        case 'JSON' :
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $data       =   json_encode($data,$json_option);
            break;
        case 'JSONP':
            // 返回JSON数据格式到客户端 包含状态信息
            header('Content-Type:application/json; charset=utf-8');
            $handler    =   isset($_GET[C('VAR_JSONP_HANDLER')]) ? $_GET[C('VAR_JSONP_HANDLER')] : C('DEFAULT_JSONP_HANDLER');
            $data       =   $handler.'('.json_encode($data,$json_option).');';
            break;
        case 'EVAL' :
            // 返回可执行的js脚本
            header('Content-Type:text/html; charset=utf-8');
            break;
    }
    exit($data);
}
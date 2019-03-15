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
 * @param string $way 请求类型
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
function validate(array $paramRules = [],$way = 'post'){
    $params = [];
    $flag = true;//重复参数标识
    foreach($paramRules as $paramName => $rules){
        $paramValue = I($way.'.'.$paramName);
        if(!$paramValue){
            //是否为必须参数
            if(isset($rules[1])){
                if($rules[1] === true)
                    showError(10006,$paramName.' is required.');
                else{
                    if($rules[1] !== false)
                        $params[$paramName] = $rules[1];//默认值
                    continue;
                }
            }else{
                //是否可以为空或者为0
                if(isset($rules[2]) && !$rules[2])
                    showError(10006,$paramName.' can not empty.');
                else
                    continue;
            }
        }
        //参数验证
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
                            showError(10006,$paramName.' is error.');
                        break;
                    case 'not in':
                        if(!is_array($filters))
                            showError(10007);
                        if(in_array($paramValue,$filters))
                            showError(10006,$paramName.' is error.');
                        break;
                    case 'num':
                        if(!is_numeric($paramValue))
                            showError(10006,$paramName.' is not a number.');
                        break;
                    case 'phone':
                        if(!preg_match('/^1[0-9]{10}$/',$paramValue))
                            showError(10006,$paramName.' is not a phone number.');
                        break;
                    case 'email':
                        $email = '/\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*/';
                        if(!preg_match($email,$paramValue))
                            showError(10006,$paramName.' is not a email.');
                        break;
                    case 'time':
                        $paramValue = strtotime($paramValue);
                        break;
                    case 'password':
                        $paramValue = encodePassword($paramValue);
                        break;
                    default:
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
                }else{
                    if($rules[3][0] === 'eq'){
                        $params[$rules[3][1]] = $paramValue;
                    }else{
                        $params[$rules[3][1]] = [$rules[3][0],$paramValue];
                    }
                }
            }else{
                if($rules[3] === 'eq'){
                    $params[$paramName] = $paramValue;
                }else{
                    $params[$paramName] = [$rules[3],$paramValue];
                }
            }
        }else
            $params[$paramName] = $paramValue;
    }
    return $params;
}

/**
 * 密码加密
 * @param string $password
 * @return string
 */
function encodePassword($password = ''){
    return sha1(md5($password));
}


/**
 * 数据库结果处理
 * @param array $rules 处理规则
 * @param array $records 待处理数据
 * @return array
 */
function handleRecords($rules,$records = []){
    if($rules && $records){
        $translate = C('TRANSLATE');
        array_walk($records,function(&$v) use($rules,$translate){
            foreach($rules as $field => $rule){
                if(!isset($rule[0]) || !isset($rule[1]))
                    showError(10008);
                if(isset($v[$field])){
                    $key = isset($rule[2]) ? $rule[2] : $field;
                    switch($rule[0]){
                        case 'translate':
                            if(isset($translate[$rule[1]]))
                                $v[$key] = $translate[$rule[1]][$v[$field]];
                            break;
                        case 'time':
                            $v[$key] = date($rule[1],$v[$field]);
                            break;
                        default:
                            break;
                    }
                }
            }
        });
    }
    return $records;
}

/**
 * 接口返回数据
 * @param array $data
 */
function returnResult($data = []){
    $result = [
        'code' => 1,
        'data' => $data
    ];
    ajaxReturn($result);
}

/**
 * 错误信息提示
 * @param string $errorCode 错误代码
 * @param string $errorMsg 错误信息
 */
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

/**
 * 获取菜单，按钮，权限数据
 * @param int $roleId
 * @return bool
 */
function getNodeData($roleId = 0){
    $modelFlag = false;
    $cacheData = [
        'menu'   => [],
        'button' => [],
        'access' => []
    ];
    foreach ($cacheData as $k => $v){
        $cache = S('role_'.$k.'_'.$roleId);
        if(!$cache){
            $modelFlag = true;
            break;
        }
    }
    if($modelFlag){
        $roleModel = M('tr_sys_role');
        $roleNode = $roleModel->where("id=".$roleId)->getField('access_node');
        if($roleNode){
            $nodeModel = M('tr_sys_node');
            $nodeData = $nodeModel->field('id,name,path,pid,type')
                ->where("state = 1 and id in(".$roleNode.")")
                ->order('sort desc')->select();
            if($nodeData){
                list($menuData,$buttonData) = formatNode($nodeData);
                $accessData = array_column($nodeData,'is_login','path');
                unset($accessData['']);
                $cacheData = [
                    'menu'   => $menuData,
                    'button' => $buttonData,
                    'access' => $accessData
                ];
            }
        }
        foreach($cacheData as $k => $v){
            if(empty($v))
                return false;
            S('role_'.$k.'_'.$roleId,$v);
        }
    }
    return true;
}

/**
 * 递归 格式化 菜单 按钮 数据
 * @param array $nodes
 * @param int $pid
 * @return array
 */
function formatNode($nodes = [],$pid = 0){
    $menuData = $buttonData = $buttons = [];
    foreach($nodes as $key => $node) {
        if ($node['pid'] == $pid) {
            $menu = [
                'name' => $node['name'],
                'path' => $node['path'],
            ];
            unset($nodes[$key]);
            if ($node['type'] != 1) {
                $buttonData[$node['type']] = $menu;
                continue;
            } else {
                list($menuChildren,$buttonChildren) = formatNode($nodes, $node['id']);
                if ($menuChildren)
                    $menu['children'] = $menuChildren;
                if ($buttonChildren){
                    if($node['path']){
                        $buttonData[$node['path']] = $buttonChildren;
                    }else{
                        $buttonData = array_merge($buttonData,$buttonChildren);
                    }
                }
                $menuData[] = $menu;
            }
        }
    }
    return [$menuData,$buttonData];
}

/**
 * 权限检测
 * @return bool
 */
function checkAccess(){
    $path = strtolower(CONTROLLER_NAME.'/'.ACTION_NAME);
    return isset($_SESSION['accessData'][$path]) && $_SESSION['accessData'][$path];
}

/**
 * 判断数据库链接,5秒钟未正常连接则返回false
 * @return bool
 */
function check_mysql(){
    $mysqli = mysqli_init();
    $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
    $conn = $mysqli->real_connect(C('DB_HOST'),C('DB_USER'),C('DB_PWD'),C('DB_NAME'),C('DB_PORT'));
    return $conn ? true : false;
}
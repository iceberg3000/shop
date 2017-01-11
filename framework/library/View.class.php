<?php
//模板函数
class View {
	/**
	 * 商品列表过滤项的URL生成
	 * @param $type 生成的URL类型（cid, price, order)
	 * @parma $data 相应的数据当前的值（为空表示清除该参数）
	 * @return string 生成好的携带正确参数的URL
	 */
    public static function mkFilterURL($type, $data='') {
		$params = $_GET;         //先取出所有参数
		unset($params['page']);  //清除分页
		if($type=='cid'){        //切换分类时清除价格
			unset($params['price']);
		}
		if($data){   //添加到参数
			$params[$type] = $data;
		}else{       //$data为空时清除参数
			unset($params[$type]);
		}
		$url = http_build_query($params);

		return $url;
		//生成 Rewrite模式 或 GET模式 链接
		//return URL_REWRITE ? str_replace(array('&','='),'/',$url).C('URL_SUFFIX') : "?$url";
   }
}
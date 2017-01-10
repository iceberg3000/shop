<?php
//分页类
class Page{
	private $options;
	/**
     * 分页类构造方法
     * @param int $total 总记录数
     * @param int $pagesize 每页显示的条数
     * @param int $current 当前页
     */
    public function __construct($total,$size,$page,$num=5){
		$this->options = array(
			'total' => $total, //总记录数
			'size' => $size,   //每页显示记录数
			'page' => max((int)$page,1), //当前访问的页码，最低为1
			'maxpage' => max(ceil($total/$size),1),  //计算总页数
			'num' => floor($num/2)  //计算当期页前后显示的相关链接个数
		);
    }
	//生成URL参数
	private function _getUrlParams(){
		$params = $_GET;
		unset($params['page']);
		return http_build_query($params);
	}
	//生成分页链接
	public function show(){
		extract($this->options); //释放成员属性
		$url = $this->_getUrlParams(); //获取URL参数字符串
		//生成Rewrite模式 或 GET模式链接
		define('URL_REWRITE', false);
		if(URL_REWRITE){
			$url = $url ? '/'.str_replace(array('&','='),'/',$url).'/page/' : '/page/';
			$suffix = C('URL_SUFFIX');
		}else{
			$url = $url ? "/shop/index.php?$url&page=" : '/shop/index.php?page=';
			$suffix = '';
		}
		$html = ''; //保存拼接结果
		//生成首页、上一页
		if($page > 1){
			$html .= "<a href=\"{$url}1$suffix\">首页</a>";
			$html .= '<a href="'.$url.($page-1).$suffix.'">上一页</a>';
		}else{
			$html .= '<span>首页</span>';
			$html .= '<span>上一页</span>';
		}
		//生成分页导航
		$start = $page - $num;
		$end = $page + $num;
		if($start < 1){
			$end = $end+(1-$start);
			$start = 1;
		}
		if($end > $maxpage){
			$start = $start-($end-$maxpage);
			$end = $maxpage;
		}
		($start < 1) && $start = 1;
		($start > 1) && $html .= '<i>...</i>';
		for($i=$start; $i<=$end; ++$i){
			if($i == $page){
				$html .= "<a href=\"{$url}{$i}{$suffix}\" class=\"curr\">$i</a>";
			} else {
				$html .= "<a href=\"{$url}{$i}{$suffix}\">$i</a>";
			}
		}
		($end < $maxpage) && $html .= '<i>...</i>';
		//生成下一页、尾页
		if($page == $maxpage){
			$html .= '<span>下一页</span>';
			$html .= '<span>尾页</span>';
		}else{
			$html .= '<a href="'.$url.($page+1).$suffix.'">下一页</a>';
			$html .= "<a href=\"{$url}{$maxpage}{$suffix}\">尾页</a>";
		}
		//返回结果
		return $html;
	}
	//获取SQL中的limit条件
	public function getLimit(){
		return ($this->options['page']-1) * $this->options['size'].','.$this->options['size'];
	}
}
<?php
//验证码类
class Captcha{
	private $name = 'captcha'; //验证码在SESSION中保存的名字
	private $len = 5; //验证码位数
	private $charset = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; //随机因子
	//创建验证码
	public function create(){
		//创建图片资源
		$im = imagecreate($x=250,$y=62);
		//随机生成背景颜色 
		$bg = imagecolorallocate($im,rand(50,200),rand(0,155),rand(0,155));
		//设置字体颜色和样式
		$fontColor = imagecolorallocate($im,255,255,255);
		$fontstyle = LIBRARY_PATH.'file'.DS.'captcha.ttf'; 
		//生成验证码字符串
		$captcha = $this->createCode();
		//生成指定长度的验证码
		for($i=0; $i<$this->len; $i++ ){
		  imagettftext($im,
			  30, 						//字符尺寸
			  rand(0,20) - rand(0,25),  //随机设置字符倾斜角度
			  32 + $i*40, rand(30,50),  //随机设置字符坐标
			  $fontColor, 				//字符颜色
			  $fontstyle, 				//字符样式
			  $captcha[$i] 				//字符内容
		  );
		}
		//将验证码存放到会话中
		isset($_SESSION) || session_start();
		session($this->name,$captcha,'save');
		//添加8个干扰线
		for($i=0; $i<8; ++$i){
			//随机生成干扰线颜色
			$lineColor = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
			//随机生成干扰线
			imageline($im,rand(0,$x),0,rand(0,$x),$y,$lineColor);
		}  
		//添加250个噪点
		for($i=0; $i<250; ++$i) {
			//随机生成噪点位置
			imagesetpixel($im,rand(0,$x),rand(0,$y),$fontColor);
		}
		//设置发送的信息头内容
		header('Content-type:image/png');
		//输出图片
		imagepng($im);
		//释放图片所占内存
		imagedestroy($im); 
	}
	//对验证码进行验证
	public function verify($input){
		$captcha = session($this->name);
		if(!empty($captcha)){
			session($this->name,'','unset'); //清除验证码，防止重复验证
			return strtoupper($captcha) == strtoupper($input); //不区分大小写
		}
		return false;
	}
	//生成随机码
	private function createCode() {
		$code = '';
		$_len = strlen($this->charset)-1;
		for($i=0;$i<$this->len;$i++) {
			$code .= $this->charset[mt_rand(0,$_len)];
		}
		return $code;
	}
}
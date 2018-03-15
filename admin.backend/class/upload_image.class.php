<?php

	//图片处理的类，包含图片上传
	class upload_image{
		//设定一个可以接收的图片的类型（MIME）
		private $accept;
		private $accept2;
		public $errinfo = '';

		public function __construct(){
			$this->accept = $GLOBALS['IMG_UP']['image_mime'];
			$this->acceptt = $GLOBALS['IMG_UP']['text_mime'];
		}

		/*
		 * 上传图片的功能
		 *
		 * @param array $file，需要上传的一个图片资源
		 *
		 * @return string $position，文件最终保存的位置和名字，如果失败则返回失败原因
		*/
		public function upload($file){
			//保证文件能能够正常上传
			//1.	判断文件是否上传成功
			if($file['error'] == 0){
				//只有上传成功之后才进行相关处理
				//假设，图片被限制成100K，但是整体上传是2M
				if($file['size'] > $GLOBALS['IMG_UP']['image_upload_size']){
					$this->errinfo = '图片上传不能大于1M'; 
					return false;
				}

				//判断图片类型
				if(!in_array($file['type'],$this->accept)){
					$this->errinfo = '图片上传的格式不正确，允许上传的图片格式有：' . implode(',',$this->accept);
					return false;
				}

				//对文件进行重命名
				@$filename = $this->getRandomName($file);
				//文件后缀名
				$filename .= $this->getExtension($file['name']);
				
				//移动文件到指定目录
				if(move_uploaded_file($file['tmp_name'],$file['path'].$filename)){
					//上传成功
					return $file['path'].$filename;
				}else{
					//移动失败
					$this->errinfo = '文件上传失败';
					return false;
				}
			}elseif($file['error'] == 1 || $file['error'] == 2){
				//文件大小超过服务器的限制
				$this->errinfo = '文件大小超出允许范围';
			}elseif($file['error'] == 3){
				//文件部分上传
				$this->errinfo = '文件上传失败，只有部分上传';
			}elseif($file['error'] == 4){
				$this->errinfo = '没有选中要上传的文件';
			}elseif($file['error'] == 6 || $file['error'] == 7){
				//服务器的错误，就不应该提示给用户，应该写到错误日志
			}
			return false;
		}

		public function textupload($file){
			if($file['error'] == 0){
				//只有上传成功之后才进行相关处理
				//假设，图片被限制成100K，但是整体上传是2M
				if($file['size'] > $GLOBALS['IMG_UP']['text_upload_size']){
					$this->errinfo = '文件上传不能大于512M'; 
					return false;
				}

				//判断图片类型
				// if(!in_array($file['type'],$this->acceptt)){
				// 	$this->errinfo = '文件上传的格式不正确，允许上传的文档格式有：' . implode(',',$this->acceptt);
				// 	return false;
				// }

				//对文件进行重命名
				@$filename = $this->getRandomName($file);
				//文件后缀名
				$filename .= $this->getExtension($file['name']);
				$file['new_name'] = $filename;
				//移动文件到指定目录
				if(move_uploaded_file($file['tmp_name'],$file['path'].$filename)){
					//上传成功
					$file['paths'] = $file['path'].$filename;
					return $file;
				}else{
					//移动失败
					$this->errinfo = '文件上传失败';
					return false;
				}
			}elseif($file['error'] == 1 || $file['error'] == 2){
				//文件大小超过服务器的限制
				$this->errinfo = '文件大小超出允许范围';
			}elseif($file['error'] == 3){
				//文件部分上传
				$this->errinfo = '文件上传失败，只有部分上传';
			}elseif($file['error'] == 4){
				$this->errinfo = '没有选中要上传的文件';
			}elseif($file['error'] == 6 || $file['error'] == 7){
				//服务器的错误，就不应该提示给用户，应该写到错误日志
			}
			return false;
		}

		/*
		 * 获得一个随机文件名字
		 * 
		 * @return string $filename，返回生成的随机文件名
		*/
		private function getRandomName(){
			//取时间戳的后十位
			$time = explode(" ",microtime());

			//拼凑名字的随机部分
				$file_name = $time[1] . substr($time[0],2,6);
			return $file_name;
		}

		/*
		 * 获得文件后缀名
		 *
		 * @param1 string $filename，上传的文件的本身的名字
		 *
		 * @return 返回一个后缀名.
		*/
		private function getExtension($filename){
			return substr($filename,strrpos($filename,'.'));
		}
		
		private function getImagecreatefrom($filename){
			$str = $this->getMIME($filename);
			return 'imagecreatefrom'.$str;
		}

		/*
		 * 生成缩略图
		 * 
		 * @param1 string $filename，需要生成缩略图的原文件
		 * @param2 int $max_width，缩略图最大宽度
		 * @param3 int $max_height，缩略图最大高度
		 *
		 * @return，成功返回图片路径，失败返回FALSE
		*/
		public function makeThumb($filename,$max_width = 100,$max_height = 100){
			
			//1.	保证原文件是一张图片
			if(!is_file($filename)) {
				//不是文件
				file_put_contents('record.txt','file not exists' . $filename);
				return false;
			}
			//获取文件信息
			$srcinfo = getimagesize($filename);
			$imagecreatefrom = $this->getImagecreatefrom($filename);
			//2.	获取原文件资源
			$src_img = $imagecreatefrom($filename);

			//3.	创建缩略图资源
			$dst_img = imagecreatetruecolor($max_width,$max_height);

			//补白(填充白色背景)
			$dst_bg = imagecolorallocate($dst_img,255,255,255);
			imagefill($dst_img,0,0,$dst_bg);

			//4.	创建缩略图
			//求得宽高比，得出基础边（宽或者高），得出具体的缩略图的宽和高
			$src_cmp = $srcinfo[0] / $srcinfo[1];
			$dst_cmp = $max_width / $max_height;
			if($src_cmp >= $dst_cmp){
				$dst_width = $max_width;
				$dst_height = round($max_width / $src_cmp);
			}else{
				$dst_height = $max_height;
				$dst_width = round($dst_height * $src_cmp);
			}

			//采样和复制
			if(imagecopyresampled($dst_img,$src_img,round(($max_width - $dst_width)/2),round(($max_height - $dst_height)/2),0,0,$dst_width,$dst_height,$srcinfo[0],$srcinfo[1])){
				//缩略图创建成功
				//获得缩略图名字
				$thumbname = 'thumb_' . $this->getRandomName($file);
				$thumbname .= $this->getExtension($filename);
				//保存缩略图
				imagepng($dst_img,UPLOAD_DIR . $thumbname);

				//返回缩略图路径
				$return =  'Public/Uploads/' . $thumbname;
			}else{
				//缩略图创建失败
				file_put_contents('record.txt','failure');
				$return = false;
			}

			return $return;

		}

		/*
		 * 制作水印图
		 *
		 * @param1 string $dst_image，需要添加水印的目标图片
		 * @param2 string $water_image，水印图片
		 * @param3 int $position，水印添加的位置
		 * @param3 int $pct，水印的透明度
		 * 
		 * @return，成功则返回水印图片地址，失败则返回FALSE
		*/
		public function makeWater($dst_image,$water_image = '',$position = 1,$pct = 60){
			//保证两张图片的是正确的
			if(!is_file($dst_image)){
				//目标文件不存在则失败
				return false;
			}
			$imagecreatefrom = $this->getImagecreatefrom($dst_image);
			//判断水印图片
			if(empty($water_image) || !is_file($water_image)){
				//如果用户没有传入水印图片，就使用系统提供的水印图片
				$water_image = $GLOBALS['config']['water_img'];
			}

			//获取图片信息
			$dstinfo = getimagesize($dst_image);
			$waterinfo = getimagesize($water_image);

			//获取图片类型，只要MIME后部分
			$mime = $this->getMIME($dst_image);

			//判断图片位置
			switch($position){
				case 1:
					//左上角
					$dst_x = 0;
					$dst_y = 0;
					break;
				case 2:
					//右上角
					$dst_x = $dstinfo[0] - $waterinfo[0];
					$dst_y = 0;
					break;
				case 3:
					//中间
					$dst_x = round(($dstinfo[0] - $waterinfo[0])/2);
					$dst_y = round(($dstinfo[1] - $waterinfo[1])/2);
					break;
				case 4:
					//左下角
					$dst_x = 0;
					$dst_y = $dstinfo[1] - $waterinfo[1];
					break;
				case 5:
				default:
					//右下角
					$dst_x = $dstinfo[0] - $waterinfo[0];
					$dst_y = $dstinfo[1] - $waterinfo[1];
					break;
			}
			//准备图片资源
			//拼凑类型
			$create = 'imagecreatefrom' . $mime;
			$save = 'image' . $mime;
			$dst_img = $create($dst_image);

			$wat_img = imagecreatefrom($water_image);

			//图片合并
			if(imagecopymerge($dst_img,$wat_img,$dst_x,$dst_y,0,0,$waterinfo[0],$waterinfo[1],$pct)){
				//水印图制作成功
				//获取水印图片名称（新作一张图片）
				$watername = 'water_' . $this->getRandomName($file) . $this->getExtension($dst_image);

				//保存图片
				$save($dst_img,UPLOAD_DIR . $watername);

				$return = 'Public/Uploads/' . $watername;
			}else{
				//水印图制作失败
				$return = false;
			}
			return $return;
		}

		/*
		 * 根据图片获取其MIME类型的后部分
		 *
		 * @param1 string $filename，文件名称image/jpeg
		 *
		 * @return 返回类型jpeg
		*/
		public function getMIME($filename){
			//判断文件是否存在
			if(!is_file($filename)) return false;
			
			//获取文件信息
			$fileinfo = getimagesize($filename);

			//获取文件的mime类型
			$mime =  explode('/',$fileinfo['mime']);
			return $mime[1];
		}

		
	}

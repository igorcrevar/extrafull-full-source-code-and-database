<?php
/*=============================================================================
|| ##################################################################
||	Extrafull
|| ##################################################################
||
||	Copyright		: (C) 2007-2009 Igor Crevar
||
|| ##################################################################
=============================================================================*/
class Image{
	protected $src = false;
	protected $width;
	protected $height;
	protected $valid = array( 'jpg', 'gif', 'png' );
  
	public function __construct( $path, $upload = false, $maximum = 786432 ){
		if ( $upload ){
			if ( !isset($_FILES[$upload]) ||  $_FILES[$upload]['error'] != UPLOAD_ERR_OK ) return;
			$ext = $this->getExt( $_FILES[$upload]['name'] );
			move_uploaded_file( $_FILES[$upload]['tmp_name'], $path );
		}
		else{
			$ext = $this->getExt( $path );
		}
		if ( in_array( $ext, $this->valid ) ){
			list( $this->width, $this->height ) = getimagesize( $path );
			if ( $this->width * $this->height > $maximum ){
				if ( $upload ) @unlink( $path );
				return;
			}
			switch ( $ext ){
			case 'jpg':
				$this->src = imagecreatefromjpeg( $path );
				break;
			case 'gif':
				$this->src = imagecreatefromgif( $path );
				break;
			case 'png':
				$this->src = imagecreatefrompng( $path );
				break;
			}			
		}
		else if ( $upload ){
			@unlink( $path );
		}
	}
	
	public function getExt( $fn ){
		$pos = strrpos( $fn, '.' ); 
		$ext = '';
		if ( $pos !== false  &&  $pos + 1 < strlen($fn) ){
      		$ext = strtolower( substr( $fn, $pos+1 ) );
    	}
    	return $ext;
	}
	
	public function isOk(){
		return $this->src !== false;
	}

	public function thumb( $width, $height, $target ){
		$dst = ImageCreateTrueColor( $width, $height );
		imagecopyresampled( $dst, $this->src, 0, 0, 0, 0, $width, $height, $this->width, $this->height );
 		imagejpeg( $dst, $target );
 		imagedestroy( $dst );
	}
	
	public function shrink( $maximum ){
		if ( $this->width <= $maximum  &&  $this->height <= $maximum ) return false;
		if ( $this->width >= $this->height ){
   	  		$width = $maximum;
    		$height = (int)($width *  $this->height / $this->width );
 		}
	  	else{  	   		  	   	
			$height = $maximum;
			$width = (int)($height * $this->width / $this->height );
  		}  				
		$dst = ImageCreateTrueColor( $width, $height );
		imagecopyresampled( $dst, $this->src, 0, 0, 0, 0, $width, $height, $this->width, $this->height );
 		imagejpeg( $dst, $target );
 		imagedestroy( $src );
 		$this->src = &$dst;
 		return true;
	}

	public function calc( $thumbW = 100, $thumbH = 75 ){
	 	if ( $this->width >= $this->height ){
 		 	$tmpH = (int)( $thumbW * $this->height / $this->width );	 
		 	$tmpW = $thumbW;
 			if ( $tmpH > $thumbH ){			 	 		 	
 				$tmpH = $thumbH;
 				$tmpW = (int)( $thumbH * $tmpW / $tmpH );
 			}
 		}
	  	else{  	   		  	   	
 			$tmpH = $thumbH;
 			$tmpW = (int)( $thumbH * $this->width / $this->height );
  		}  		
		return array( 'w' => $tmpW, 'h' => $tmpH );
	}

	public function addWatermark( $path, $xInc=0, $yInc=16 ){
		if ( !file_exists( $path ) ) return false;
		$watermark = imagecreatefrompng( $path );
		$width = imagesx( $watermark );  
		$height = imagesy( $watermark );    
		$x = $this->width - $width + $xInc;
		$y = $this->height - $height + $yInc;  
		imagecopy( $this->src, $watermark, $x, $y, 0, 0, $width, $height );
		imagedestroy( $watermark );   
		return true;     
	}
	
	public function save( $path ){
		imagejpeg( $this->src, $path );
	}
	
	public function destroy(){
		if ( $this->src ){
			imagedestroy( $this->src );
		}
		$this->src = false;
	}
	
	public function __destruct(){ //only for php5
		$this->destroy();
	}
}

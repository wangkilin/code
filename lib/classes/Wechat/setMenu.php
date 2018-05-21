<?php
/********************************************************/
/*****                 @!!@                          ****/
/********************************************************/
/**
 *@FileName    : setMenu.php
 *@Author      : Kilin WANG <wangkilin@126.com>
 *@Date        : 2017年6月14日
 *@Homepage    : http://www.kinful.com
 *@Version     : 0.1
 */

require_once(dirname(__FILE__) . '/Request.class.php');

$domain = 'http://www.icodebang.com';

$config =  array(
		// 配置文件
		'REPORT_CLICK_URL' => 'http://dig-visitor.oradt.com/fvisitor',
		'Neo4j'            => array (
				'url'      => 'http://118.186.66.169:80/db/data/transaction',
				'user'     => 'neo4j',
				'pass'     => 'neo654321',
		),
		// 微信配置
		'Wechat'           => array (
				'AppID'             => 'wxd25e67dfa7b2dd59',
				'AppSecret'         => 'd91611978da6c8b67f41a9945c0599ae',
				'Token'             => 'TokenForKilinPublicPlatform',
				'EncodingAESKey'    => 'S0vTZ2ZhED24Fo5kt4niVIaHWjdKOHumV4GBoN1sGH0',
				'menu'              => array (
						'button' => array (
								array('name'=>'案例展示',
										'sub_button'=> array(
												array(  'type'=>'click',
														'key' =>'english',
														'name'=>'英语在线'),
												array(  'type'=>'click',
														'key' =>'travel',
														'name'=>'旅游景点'),
												array(  'type'=>'click',
														'key' =>'icodebang',
														'name'=>'爱码帮'),
												array(  'type'=>'click',
														'key' =>'edulong',
														'name'=>'育龙科技'),
												array(
														'type'=>'view',
														'key' =>'view',
														'url' =>$domain. '/h5/wechat/software',
														'name'=>'小程序'),
										)
								),
								array('name'=>'拍照扫码',
										'sub_button'=> array(
												array(
														'type'=>'scancode_push',
														'key'=>'scancode_push',
														'url'=>$domain. '/h5/wechat/scanCodePush',
														'name'=>'扫一扫'),
												array(
														'type'=>'scancode_waitmsg',
														'key'=>'scancode_waitmsg',
														'url'=>$domain. '/h5/wechat/scanCodeWaitMsg',
														'name'=>'scancode_waitmsg'),
												array(
														'type'=>'pic_sysphoto',
														'key'=>'pic_sysphoto',
														'url'=>$domain. '/h5/wechat/picSysPhoto',
														'name'=>'pic_sysphoto'),
												array(
														'type'=>'pic_photo_or_album',
														'key'=>'pic_photo_or_album',
														'url'=>$domain. '/h5/wechat/picPhotoOrAlbum',
														'name'=>'pic_photo_or_album'),
												array(
														'type'=>'pic_weixin',
														'key'=>'pic_weixin',
														'url'=>$domain. '/h5/wechat/picWeixin',
														'name'=>'pic_weixin'),
										)
								),
								array('name'=>'爱码帮',
										'sub_button'=> array(
												array(
														'type'=>'location_select',
														'key'=>'location_select',
														'url'=>$domain. '/h5/wechat/locationSelect',
														'name'=>'location_select'),
				                                array('type'=>'view',
				                                	  'key' =>'aboutus',
				                                	  'url' =>$domain . '/h5/aboutus',
				                                	  'name'=>'关于我们'),
				                                array('type'=>'view',
				                                	  'key' =>'sitecop',
				                                	  'url' =>$domain . '/h5/cooperate',
				                                	  'name'=>'网站合作'),
												//                                                 array(
												//                                                         'type'=>'media_id',
												//                                                         'key'=>'media_id',
												//                                                         'url'=>'http://dev.orayun.com/demo/wechat/mediaId',
												//                                                         'name'=>'media_id'),
												//                                                 array(
												//                                                         'type'=>'view_limited',
														//                                                         'key'=>'view_limited',
														//                                                         'url'=>'http://dev.orayun.com/demo/wechat/viewLimited',
														//                                                         'name'=>'view_limited'),
												)
								),

						)
				)
		),
);

$request = new Request($config['Wechat']['AppID'],
		               $config['Wechat']['AppSecret']
		);

//echo $request->getAccessToken(), "\r\n";

$request->setMenu($config['Wechat']['menu']);

echo $request->getMenu();

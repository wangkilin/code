<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>

<rss version="2.0"
	xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:wfw="http://wellformedweb.org/CommentAPI/"
	xmlns:dc="http://purl.org/dc/elements/1.1/"
	xmlns:atom="http://www.w3.org/2005/Atom"
	xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
	xmlns:slash="http://purl.org/rss/1.0/modules/slash/"
>
	<channel>
		<title><?php echo get_setting('site_name'); ?></title>
		<atom:link href="<?php echo get_js_url('/feed/'); ?>" rel="self" type="application/rss+xml" />
		<link><?php echo base_url(); ?></link>
		<description><?php echo get_setting('description'); ?></description>
		<language>zh-cn</language>
		<generator>WeCenter</generator>
		<?php foreach($this->list as $key => $val){ ?>
		<item>
			<title><?php echo $val['question_content']; ?></title>
			<link><?php echo get_js_url('/question/' . $val['question_id']); ?></link>
			<description><![CDATA[<?php echo FORMAT::parse_attachs(nl2br(FORMAT::parse_bbcode($val['question_detail']))); ?>]]></description>
			<pubDate><?php echo date('r', $val['add_time']); ?></pubDate>
			<guid><?php echo get_js_url('/question/' . $val['question_id']); ?></guid>
		</item>
		<?php } ?>
	</channel>
</rss>
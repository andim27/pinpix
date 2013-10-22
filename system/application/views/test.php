<script type="text/javascript" src="<?php echo base_url(); ?>js/highslide/highslide-with-html.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js/highslide/highslide.css" />

<!--
	2) Optionally override the settings defined at the top
	of the highslide.js file. The parameter hs.graphicsDir is important!
-->

<script type="text/javascript">
	hs.graphicsDir = '<?php echo base_url(); ?>js/highslide/graphics/';
	hs.outlineType = 'rounded-white';
	hs.showCredits = false;
	hs.wrapperClassName = 'draggable-header';

</script>

</head>

<body>

<div>
<!--
	3) Mark up the main content like this to use a self rendering content wrapper with inline
	main content. The content is grabbed from the first subsequent
	div with a class name of .highslide-maincontent.
-->
<a href="index.htm" onclick="return hs.htmlExpand(this, { headingText: 'Lorem ipsum' })">
	Open HTML-content
</a>
<div class="highslide-maincontent">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aliquam dapibus leo quis nisl. In lectus. Vivamus consectetuer pede in nisl. Mauris cursus pretium mauris. Suspendisse condimentum mi ac tellus. Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Donec sed enim. Ut vel ipsum. Cras consequat velit et justo. Donec mollis, mi at tincidunt vehicula, nisl mi luctus risus, quis scelerisque arcu nibh ac nisi. Sed risus. Curabitur urna. Aliquam vitae nisl. Quisque imperdiet semper justo. Pellentesque nonummy pretium tellus.
</div>




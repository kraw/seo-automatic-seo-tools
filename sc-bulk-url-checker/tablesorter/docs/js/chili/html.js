/*
===============================================================================
Chili is the jQuery code highlighter plugin
...............................................................................
                                               Copyright 2007 / Andrea Ercolino
-------------------------------------------------------------------------------
LICENSE: http://www.opensource.org/licenses/mit-license.php
WEBSITE: http://noteslog.com/chili/
===============================================================================
*/

{
    steps: {
          mlcom : { exp: /\<!--(?:.|\n)*?--\>/ }
        , tag   : { exp: /(?:\<\!?[\w:]+)|(?:\>)|(?:\<\/[\w:]+\>)|(?:\/\>)/ }
		, php   : { exp: /(?:\<\?php\s)|(?:\<\?)|(?:\?\>)/ }
        , aname : { exp: /\s+?[\w-]+:?\w+(?=\s*=)/ }
        , avalue: { 
			  exp: /(=\s*)(([\"\'])(?:(?:[^\3\\]*?(?:\3\3|\\.))*?[^\3\\]*?)\3)/
			, replacement: '$1<span class="$0">$2</span>' }
        , entity: { exp: /&[\w#]+?;/ }
    }
}
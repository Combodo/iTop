package iTop
{
	import flash.display.*;
	import flash.geom.*;
	import flash.net.*;
	import flash.events.*;
	import flash.text.*; 
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	import iTop.ToolTip;
	import iTop.Navigator;
	
	// Items to load on the main chart
	public class GraphNode extends Sprite
	{
		private var m_oIcon:Loader;
		private var m_sClass;String;
		private var m_iId:Number;
		private var m_sParentKey:String;
		private var m_oToolTip:ToolTip;
		private var m_fZoom:Number;
		private var m_oParent:Navigator;
		
		public function GraphNode(oParent:Navigator, oPt:Point, sClass:String, iId:Number, sLabel:String, sIconPath:String, sParentKey:String, fZoom:Number)
		{
			x = oPt.x;
			y = oPt.y;
			m_fZoom = fZoom;
			m_sClass = sClass;
			m_iId = iId;
			m_sLabel.text = sLabel;
			m_sLabel.autoSize = TextFieldAutoSize.CENTER;
			m_sLabel.width = m_sLabel.textWidth;
			m_sLabel.x = -m_sLabel.width/2;
			m_sLabel.height = m_sLabel.textHeight;
			m_sParentKey = sParentKey;
			m_oToolTip = new ToolTip( "<p><b>"+m_sClass+"</b></p><p>"+sLabel+"</p>");
			m_oToolTip.scaleX = 1 / m_fZoom;
			m_oToolTip.scaleY = 1 / m_fZoom;
			m_oParent = oParent;
			
			var myURL:URLRequest = new URLRequest(sIconPath);
			m_oIcon = new Loader();
			m_oIcon.contentLoaderInfo.addEventListener(IOErrorEvent.IO_ERROR, onLoadError);
			m_oIcon.contentLoaderInfo.addEventListener(SecurityErrorEvent.SECURITY_ERROR, onLoadError);
			m_oIcon.contentLoaderInfo.addEventListener(Event.COMPLETE, onLoadComplete);
			m_oIcon.load(myURL);
			addChild(m_oIcon);
			addEventListener(MouseEvent.MOUSE_DOWN, mouseDown)  
			addEventListener(MouseEvent.MOUSE_UP, mouseReleased); 
			addEventListener( MouseEvent.MOUSE_OVER, mouseOver );
			
			var oContextMenu:ContextMenu = new ContextMenu();
			oContextMenu.hideBuiltInItems();
            var oCMI:ContextMenuItem = new ContextMenuItem('Details...');
            oCMI.addEventListener(ContextMenuEvent.MENU_ITEM_SELECT, navigateToObjectDetails);
            oContextMenu.customItems.push(oCMI);
            this.contextMenu = oContextMenu;

		}
		
		public function GetKey()
		{
			return m_sClass+'/'+m_iId;
		}
		
		public function GetParentKey()
		{
			return m_sParentKey;
		}
		
		function onLoadError(event:ErrorEvent):void
		{
			// Display error message to user in case of loading error.
			trace ("Sorry that there is an error during the loading of an external image. The error is:" + "\n" + event); 
		}
		function onLoadComplete(event:Event):void
		{ 
			// Add the Loader on the Sprite when the loading is completed
			m_oIcon.x = -m_oIcon.width / 2;
			m_oIcon.y = -m_oIcon.height + 8; // Slightly shifted downward
			
			// Construct a tooltip
			addChild(m_oToolTip);
			addChild(m_oIcon);
			trace('m_sLabel, getChildIndex:'+getChildIndex(m_sLabel));
			trace('m_oToolTip, getChildIndex:'+getChildIndex(m_oToolTip));
			//swapChildren(m_oToolTip, );
			// Start the tooltip
			m_oToolTip.start(); 
		}
		 
		function mouseDown(event:MouseEvent):void 
		{
			trace("Click in Node");
			m_oParent.m_bChildDragging = true;
			startDrag(); 
		} 
		
		function mouseReleased(event:MouseEvent):void 
		{ 
			stopDrag(); 
			m_oParent.m_bChildDragging = false;
		}

		public function mouseOver( e:MouseEvent ):void
		{
			// Move to the top
			parent.setChildIndex( this, this.parent.numChildren-1 );
		}
		
		private function navigateToObjectDetails(evt:ContextMenuEvent):void
		{
			var sUrl:String = ReadParam('drillUrl', 'http://localhost/pages/UI.php?operation=details');
			sUrl += '&class='+m_sClass+'&id='+m_iId;
			var oReq:URLRequest = new URLRequest(sUrl);
			navigateToURL(oReq, '_top');
		}

		public function ReadParam(sName:String, sDefaultValue:String)
		{
			var paramObj:Object = LoaderInfo(this.root.loaderInfo).parameters;
		
			if (paramObj.hasOwnProperty(sName))
			{
				return unescape(paramObj[sName]);
			}
			else
			{
				return sDefaultValue;
			}
		}
	}
}
package iTop
{
	import flash.display.*;
	import flash.geom.*;
	import flash.net.*;
	import flash.events.*;
	import flash.text.*; 
	import flash.xml.*; 
	import flash.ui.ContextMenu;
	import flash.ui.ContextMenuItem;
	import iTop.ToolTip;
	import iTop.Navigator;
	
	// Items to load on the main chart
	public class GraphNode extends Sprite
	{
		private var m_oIcon:Loader;
		private var m_sClass:String;
		private var m_sClassName:String;
		private var m_iId:Number;
		private var m_sParentKey:String;
		private var m_oToolTip:ToolTip;
		private var m_fZoom:Number;
		private var m_oParent:Navigator;
		private static const ROUND:Number = 20;
		private static const PADDING:Number = 5;
		public var m_speed_x:Number = 0;
		public var m_speed_y:Number = 0;
		public var m_bInDrag:Boolean = false;
		
		public function GraphNode(oParent:Navigator, oPt:Point, sClass:String, sClassName:String, iId:Number, sLabel:String, sIconPath:String, sParentKey:String, fZoom:Number, oDetails:Object)
		{
			x = oPt.x;
			y = oPt.y;
			m_fZoom = fZoom;
			m_sClass = sClass;
			m_sClassName = sClassName;
			m_iId = iId;
			m_sLabel.autoSize = TextFieldAutoSize.LEFT;
			m_sLabel.multiline = false;
			m_sLabel.text = sLabel;
			m_sLabel.width = m_sLabel.textWidth;
			m_sLabel.x = -m_sLabel.width/2;
			m_sLabel.height = m_sLabel.textHeight;
			// Draw the background
			graphics.beginFill( 0xf1f1f6, 0.8 );
			graphics.drawRoundRect( m_sLabel.x -PADDING, m_sLabel.y - PADDING, m_sLabel.width+PADDING*2, m_sLabel.height+PADDING*2, ROUND );
			graphics.endFill();
			
			m_sParentKey = sParentKey;
			var sTooltipText:String = "<p><b>"+m_sClassName+"</b></p><p>"+sLabel+"</p>";
			for (var s:String in oDetails)
			{
				sTooltipText += '<p>'+s+': '+oDetails[s]+'</p>';
			}
			trace('Tooltip text: '+sTooltipText);
			m_oToolTip = new ToolTip(sTooltipText);
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
			m_bInDrag = true;
			m_oToolTip.timer.stop(); // Don't show the tooltip while dragging
			startDrag(); 
		} 
		
		function mouseReleased(event:MouseEvent):void 
		{ 
			m_bInDrag = false;
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
		        
		public function GetLabelWidth():Number
		{
			return m_sLabel.width;
		}
	}
}
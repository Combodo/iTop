package iTop
{
	import flash.display.*;
	import flash.geom.*;
	import flash.text.TextField;
	import flash.text.TextFormat;
	import flash.text.TextFieldAutoSize;
	import flash.text.TextLineMetrics;
	import flash.display.BlendMode;
	import flash.utils.Timer;
	import flash.events.MouseEvent;
	
	public class ToolTip extends Sprite
	{
		private var _tip:String;
		// You'll need this for proper text formatting
		private var _tf:TextField = new TextField();
		private var _format:TextFormat = new TextFormat();
		private static const ROUND:Number = 10;
		private static const HEIGHT:Number = 25;
		private static const FONT_SIZE:uint = 12;
		private static const FONT:String = 'Arial';
		private static const PADDING:Number = 5;
		private static const MIN_ALPHA:Number = 0.0;
		private static const ALPHA_INC:Number = 0.1;
		private static const MAX_ALPHA:Number = 1;
		private static const REFRESH:Number = (MAX_ALPHA - MIN_ALPHA) / ALPHA_INC;
		private static const APPEAR_TIMEOUT = 1000; // ms
		// For appearence, fading in and out
		public var timer:Timer;
		public function ToolTip( tip:String )
		{
			// Hold onto the tip for posterity
			_tip = tip;
			// This ensures the textfield inherits this class's
			// alpha property.  Very important because otherwise tf
			// would always have an alpha of 1 meaning it will always be visible
			this.blendMode = BlendMode.LAYER;
			_format.size = FONT_SIZE;
			_format.font = FONT;
			// Make sure the text behaves and looks
			// the way text on a button should
			_tf.defaultTextFormat = _format;
			// Always call defaultTextFormat before setting text otherwise
			// the text doesn't use the formatting defined in tf
			_tf.autoSize = TextFieldAutoSize.LEFT;
			// You have to set autoSize to TextFieldAutoSize.LEFT
			// for box.textWidth to be accurate
			_tf.multiline = true;
			_tf.htmlText = tip;
			_tf.selectable = false;
			_tf.x += PADDING;
			_tf.y += PADDING;
			addChild( _tf );
			// Draw the background
			graphics.beginFill( 0xEEEE99, 0.95 );
			graphics.drawRoundRect( 0, 0, _tf.textWidth+PADDING*4, _tf.textHeight+PADDING*4, ROUND );
			graphics.endFill();
			this.alpha = MIN_ALPHA;
			this.visible = false;
		}
		// You have to call this after
		// the tooltip has been added to the
		// display list
		public function start():void
		{
			this.parent.addEventListener( MouseEvent.MOUSE_OVER, mouse_over );
		}
		public function mouse_over( e:MouseEvent ):void
		{
			// Make the tooltip appear smoothly after a delay
			if (this.timer != null)
			{
				this.timer.stop();
			}
			this.timer = new Timer( APPEAR_TIMEOUT, 1 );
			this.timer.addEventListener( "timer", appear );
			this.timer.start();
			this.parent.addEventListener( MouseEvent.MOUSE_OUT, mouse_out );
		}
		public function mouse_out( e:MouseEvent ):void
		{
			var fadeSpeed:Number = 500 / REFRESH;
			if (this.timer != null)
			{
				this.timer.stop();
			}
			this.timer = new Timer( fadeSpeed, REFRESH );
			this.timer.addEventListener( "timer", fadeOut );
			this.timer.start();
			this.parent.removeEventListener( MouseEvent.MOUSE_OUT, mouse_out );
		}
		
		private function appear(i:uint):void
		{
			// The delay has elapsed, show (smoothly) the tooltip
			// Make sure that the tooltip always appears at se same scale 1:1 even
			// if the whole scene is zoomed out
			scaleX = 1 / this.parent.parent.scaleX;
			scaleY = 1 / this.parent.parent.scaleY;
			if (this.timer != null)
			{
				this.timer.stop();
			}
			// Reuse the time for the fadeIn
			this.parent.setChildIndex( this, this.parent.numChildren-1 )
			// Move the tool tip to the top!
			var fadeSpeed:Number = 500 / REFRESH;
			this.alpha = MIN_ALPHA;
			if (this.timer != null)
			{
				this.timer.stop();
			}
			this.timer = new Timer( fadeSpeed, REFRESH );
			this.timer.addEventListener( "timer", fadeIn );
			this.timer.start();
			this.parent.addEventListener( MouseEvent.MOUSE_OUT, mouse_out );
		}
		
		private function fadeIn( i:uint ):void
		{
			this.visible = true;
			if (this.alpha < (1.0 - ALPHA_INC))
			{
				this.alpha += ALPHA_INC;
			}
			else
			{
				this.alpha = 1.0;
			}
			//trace("++ Tooltip alpha: "+this.alpha+" ALPHA_INC:"+ALPHA_INC);
		}
		private function fadeOut( i:uint ):void
		{
			if (this.alpha > ALPHA_INC)
			{
				this.alpha -= ALPHA_INC;
			}
			else
			{
				this.alpha = 0.0;
				this.visible = false;
			}
			//trace("-- Tooltip alpha: "+this.alpha+" ALPHA_INC:"+ALPHA_INC);
		}
	}
}


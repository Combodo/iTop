class ImageToolkit {


	/**
	 * Get the image average components.
	 *
	 * @param imgElement image to scan
	 * @returns average components {{r: number, g: number, b: number}}
	 * @constructor
	 */
	static GetImageAverageRGB(imgElement = null) {

		let aRgb = {r: 0, g: 0, b: 0};

		// traverse rgb data
		let data = ImageToolkit.GetImageRgbData(imgElement, (rgb) => {
			aRgb.r += rgb.r;
			aRgb.g += rgb.g;
			aRgb.b += rgb.b;
		});

		// ~~ used to floor values
		return {
			r: ~~(aRgb.r / data.length),
			g: ~~(aRgb.g / data.length),
			b: ~~(aRgb.b / data.length),
		};

	}

	/**
	 *
	 * @param imgElement
	 * @returns {*}
	 * @constructor
	 */
	static GetImageDominantRGB(imgElement = null) {

		let aRgbCount = [];

		// traverse rgb data
		let data = ImageToolkit.GetImageRgbData(imgElement, (rgb) => {

			// color representation
			let color = `${rgb.r}-${rgb.g}-${rgb.b}`;

			// initialize if color doesn't exist
			if (aRgbCount[color] === undefined) {
				aRgbCount[color] = {
					rgb,
					count: 0
				};
			}

			// increment color count
			aRgbCount[color].count++;
		});


		// search maxi
		let maxi = null;
		for (let key in aRgbCount) {
			let value = aRgbCount[key];
			// ignore black
			if (value.rgb.r === 0 && value.rgb.g === 0 && value.rgb.b === 0) {
				continue;
			}
			// ignore white
			if (value.rgb.r === 255 && value.rgb.g === 255 && value.rgb.b === 255) {
				continue;
			}
			// test maxi
			if (maxi === null || maxi.count < value.count) {
				maxi = value;
			}
		}

		return maxi.rgb;
	}

	/**
	 *
	 * @param imgElement
	 * @param traversingCallback
	 * @param pixelPrecision
	 * @returns {*[]|null}
	 * @constructor
	 */
	static GetImageRgbData(imgElement, traversingCallback, pixelPrecision = 5) {

		let canvas = document.createElement('canvas'),
			context = canvas.getContext && canvas.getContext('2d'),
			data, imgWidth, imgHeight,
			rgbData = [];

		// no context or no image
		if (!context || imgElement === null) {
			return null;
		}

		// image size
		imgWidth = canvas.width = imgElement.naturalWidth || imgElement.offsetWidth || imgElement.width;
		imgHeight = canvas.height = imgElement.naturalHeight || imgElement.offsetHeight || imgElement.height;

		// draw image
		context.drawImage(imgElement, 0, 0);

		try {

			// retrieve image data
			data = context.getImageData(0, 0, imgWidth, imgHeight);

			// compute rgb data
			let i = -4;
			while ((i += pixelPrecision * 4) < data.data.length) {
				let rgb = {
					r: data.data[i],
					g: data.data[i+1],
					b: data.data[i+2]
				};
				rgbData.push(rgb);
				traversingCallback(rgb)
			}

			return rgbData;

		} catch (e) {

			return null;
		}
	}

}

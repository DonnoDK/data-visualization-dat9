function computeFFT(testCaseId, channelId, point)
{
	$.ajax({
		url: "Api/Eeg/getPoint/" + testCaseId + "/" + channelId + "/" + point,
		dataType: "json",
		success: function(response) {
			//Determine if dummy data by taking average of 5 samples
			var avg = 0;
			for (var i = 0; i < 5; i++)
			{
				avg += response.data[i];
			}
			avg /= 5;
			var isDummyData = avg < 100;

			//Sampling frequency
			var SamplingFrequency = isDummyData ? 360 : 128;

			//Compute fft
			var fft = myFFT(response.data, SamplingFrequency);
			var powerSampling = [];

			//Format
			for (var i = 0; i < (isDummyData ? SamplingFrequency / 2 : SamplingFrequency); i++)
			{
				powerSampling[i] = [i, fft.FrequencyPowerSampling[i]];
			}

			var options = {
		        series: { lines: { show: true }, shadowSize: 0 }
		    };

			//Display data

			var fftEl = $("#fft-chart");
			$.plot(fftEl, [{label: "FFT for Point y", data: powerSampling }], options);
		}
	});
}


function myFFT(samples, sampleFrequency)
{
	var obj = {}

	//Raw FFT output
	var rawFFTOutput = new complex_array.ComplexArray(samples).FFT();

	//What we usually refer to as "FFT graph"
	obj.FrequencyPowerSampling = [];

	rawFFTOutput.forEach(function(c_value, i, n){
		var magnitude = Math.sqrt(Math.pow(c_value.real, 2) + Math.pow(c_value.imag, 2));
		obj.FrequencyPowerSampling[i] = magnitude / (Math.sqrt(n) / 2);
	});

	return obj;
}


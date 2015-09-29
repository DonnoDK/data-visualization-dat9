$("#fftBtn").on('click', computeFFT);

function computeFFT()
{
	$.ajax({
		url: "Api/Eeg/getPoint/1/5/100",
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

			var options = {
		        series: { lines: { show: true }, shadowSize: 0 },
		        xaxis: { zoomRange: null, panRange: [0, response.data.length + 100] },
		        yaxis: { zoomRange: null, panRange: [0, Array.max(response.data) + 10] },
		        zoom: {
		            interactive: true
		        },
		        pan: {
		            interactive: true
		        }
		    };

			//Display data
			var fftEl = $("#fft-chart");
			//$.plot(fftEl, fft.FrequencyPowerSampling, options);
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
	
	var str = "";
	for (var i = 0; i < obj.FrequencyPowerSampling.length / 2; i++)
	{
		str += i * sampleFrequency / obj.FrequencyPowerSampling.length + "\t" + obj.FrequencyPowerSampling[i].toString().replace("e", "E") + "\n";
	}
	console.log(str);

	return obj;
}


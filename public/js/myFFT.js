var fftEl, absEl, relEl;

var frequencyPowerSamplingOptions;
var frequencyPowerSamplingData;
var absOptions;
var absData;
var relOptions;
var relData;

var prevGraph;
var errString = "";

function HideAll() {
	fftEl.hide();
	absEl.hide();
	relEl.hide();
};

function showFFT()
{
	HideAll();
	fftEl.show();
	prevGraph = showFFT;

	if (errString === "")
	{
		$.plot(fftEl, frequencyPowerSamplingData, frequencyPowerSamplingOptions);
	}
}

function showAbs()
{
	HideAll();
	absEl.show();
	prevGraph = showAbs;

	if (errString === "")
	{
		$.plot(absEl, absData, absOptions);
	}
}

function showRel(){
	HideAll();
	relEl.show();
	prevGraph = showRel;

	if (errString === "")
	{
		$.plot(relEl, relData, relOptions);
	}
}

$(document).ready(function () {
	fftEl = $("#fft-chart");
	absEl = $("#frequency-band-absolute-chart");
	relEl = $("#frequency-band-relative-chart");

	HideAll();
	fftEl.show();

	$("#btn-fft-graph").on('click', showFFT);
	$("#btn-band-abs").on('click', showAbs);
	$("#btn-band-rel").on('click', showRel);
});

function computeFFT(testCaseId, channelId, point)
{
	$.ajax({
		url: "Api/Eeg/getPoint/" + testCaseId + "/" + channelId + "/" + point,
		dataType: "json",
		success: function(response) {
			/*
				NOTE: We only recieve 256 samples, but for dummy data we need 360.
				Due to this, when FFTs are calculated for dummy data it is using real data sampling rate. This produces false results.
			*/

			if (response.data.length == 360 || response.data.length == 256)
			{
				errString = "";

				//Determine if dummy data
				var isDummyData = response.data.length === 360;

				//Sampling frequency
				var SamplingFrequency = isDummyData ? 360 : 128;
				var frequencyResolution = (isDummyData ? SamplingFrequency / 2 : SamplingFrequency);

				//Compute fft
				var fft = myFFT(response.data, SamplingFrequency);
				
				computeFrequencyPowerSampling(fft, frequencyResolution, point);
				computeBands(fft);

			    if (prevGraph === undefined)
			    {
			    	prevGraph = showFFT;
			    }

				prevGraph();
			}
			else
			{
				errString = "<p>Not enough data available past this point(" + point + ").<br>Need at least 256 points.</p>";
				fftEl.html(errString);
				absEl.html(errString);
				relEl.html(errString);
			}
		}
	});
}

function computeFrequencyPowerSampling(fft, frequencyResolution, point)
{
	var powerSampling = [];

	//Format
	for (var i = 0; i < frequencyResolution; i++)
	{
		powerSampling[i] = [i, fft.FrequencyPowerSampling[i]];
	}

	frequencyPowerSamplingOptions = {
        series: { lines: { show: true }, shadowSize: 0 },
		xaxis: { min: -5, max: frequencyResolution + 5},
		yaxis: { min: 0, max: 1},
		axisLabels: {
        	show: true
        },
        xaxes: [{
            axisLabel: 'Hz',
        }],
        yaxes: [{
            position: 'left',
            axisLabel: 'Normalized amplitude',
        }],
    };
    frequencyPowerSamplingData = [{label: "FFT for Point " + point, data: powerSampling }];
}

function computeBands(fft)
{
	var deltaDef = {lowerLimit:1, upperLimit:3, label:"Delta"},
		thetaDef = {lowerLimit:4, upperLimit:8, label:"Theta"},
		alphaDef = {lowerLimit:9, upperLimit:12, label:"Alpha"},
		betaDef = {lowerLimit:13, upperLimit:27, label:"Beta"};

	//Compute absolute band
	var absoluteBandPower = {};
	absoluteBandPower.Delta = computeAbsoluteBandPower(deltaDef, fft);
	absoluteBandPower.Theta = computeAbsoluteBandPower(thetaDef, fft);
	absoluteBandPower.Alpha = computeAbsoluteBandPower(alphaDef, fft);
	absoluteBandPower.Beta = computeAbsoluteBandPower(betaDef, fft);

	var totalFrequencyPower = 
		absoluteBandPower.Delta + 
		absoluteBandPower.Theta +
		absoluteBandPower.Alpha +
		absoluteBandPower.Beta;

	//Compute relative band
	var relativeBandPower = {};
	relativeBandPower.Delta = computeRelativeBandPower(deltaDef, absoluteBandPower, totalFrequencyPower);
	relativeBandPower.Theta = computeRelativeBandPower(thetaDef, absoluteBandPower, totalFrequencyPower);
	relativeBandPower.Alpha = computeRelativeBandPower(alphaDef, absoluteBandPower, totalFrequencyPower);
	relativeBandPower.Beta = computeRelativeBandPower(betaDef, absoluteBandPower, totalFrequencyPower);

	absOptions = {
		bars: {
            show: true,
            barWidth: 1
        },
        xaxis: { min: 0, max: 9}
	};
	absData = [{label:"Band Power, method: Absolute", data:[
		[1, absoluteBandPower.Delta],
		[3, absoluteBandPower.Theta],
		[5, absoluteBandPower.Alpha],
		[7, absoluteBandPower.Beta]]}];

	relOptions = {
		bars: {
            show: true,
            barWidth: 1
        },
        xaxis: { min: 0, max: 9}
	};
	relData = [{label:"Band Power, method: relative", data:[
		[1, relativeBandPower.Delta],
		[3, relativeBandPower.Theta],
		[5, relativeBandPower.Alpha],
		[7, relativeBandPower.Beta]]}];
}

function computeRelativeBandPower(bandFrequencyDef, absoluteBandPower, totalFrequencyPower) {
	return absoluteBandPower[bandFrequencyDef.label] / totalFrequencyPower;
}

function computeAbsoluteBandPower(bandFrequencyDef, fft)
{
	var bandPower = 0;

	fft.rawFFTOutput.forEach(function(c_value, i, n){
		if (i >= bandFrequencyDef.lowerLimit && i < bandFrequencyDef.upperLimit)
		{
			var magnitude = Math.sqrt(
				Math.pow(c_value.real, 2) + 
				Math.pow(c_value.imag, 2));
			bandPower += Math.pow(magnitude, 2);
		}
	});

	return bandPower;
}

function myFFT(samples, sampleFrequency)
{
	var obj = {}

	//Raw FFT output
	obj.rawFFTOutput = new complex_array.ComplexArray(samples).FFT();

	//What we usually refer to as "FFT graph"
	obj.FrequencyPowerSampling = [];

	obj.rawFFTOutput.forEach(function(c_value, i, n){
		var magnitude = Math.sqrt(Math.pow(c_value.real, 2) + Math.pow(c_value.imag, 2));
		obj.FrequencyPowerSampling[i] = magnitude / (Math.sqrt(n) / 2);
	});

	return obj;
}


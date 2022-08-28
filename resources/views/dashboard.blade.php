<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-center text-xl text-gray-800 leading-tight">
            {{ config('app.name') }}
        </h2>
    </x-slot>
    @section('custom_css')
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endsection

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-12 gap-6 ">
                <a href="{{route('prisoner.index',['filter[search_date]' => \Carbon\Carbon::now()->format('Y-m-d')])}}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">

                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
{{--                            'filter[search_date]' => $search_date--}}
{{--                            <a href=""></a>--}}
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">{{$prisoners_arrested_in_last_3_months}}</div>

                                <div class="mt-1 text-base  font-bold text-gray-600">
                                    Prisoners arrested in last 3 months
                                </div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <img src="https://cdn-icons-png.flaticon.com/128/6289/6289143.png" alt="employees on leave" class="h-12 w-12">

                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{route('prisoner.index',['filter[search_released]' => \Carbon\Carbon::now()->format('Y-m-d')])}}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    {{$prisoners_released_in_last_3_months}}
                                </div>
                                <div class="mt-1 text-base  font-bold text-gray-600">Released in last 3 months</div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">

                                <img src="https://cdn-icons-png.flaticon.com/512/3813/3813948.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{route('assistance.index',['filter[search_assistance]' => \Carbon\Carbon::now()->format('Y-m-d')])}}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">
                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    @if(!empty($legal_assistance->total))
                                        {{$legal_assistance->total}}
                                    @else
                                        0
                                    @endif
                                </div>
                                <div class="mt-1 text-base  font-bold text-gray-600">Legal assistance provided in last 3 months</div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">
                                <img src="https://cdn-icons-png.flaticon.com/128/3122/3122321.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
                <a href="{{route('prisoner.index',['filter[search_expected]' => \Carbon\Carbon::now()->format('Y-m-d')])}}" class="transform  hover:scale-105 transition duration-300 shadow-xl rounded-lg col-span-12 sm:col-span-6 xl:col-span-3 intro-y bg-white">

                    <div class="p-5">
                        <div class="grid grid-cols-3 gap-1">
                            <div class="col-span-2">
                                <div class="text-3xl font-bold leading-8">
                                    {{$prisoners_to_be_released_in_3_months}}
                                </div>
                                <div class="mt-1 text-base font-bold text-gray-600">To be released in next 3 months</div>
                            </div>
                            <div class="col-span-1 flex items-center justify-end">

                                <img src="https://cdn-icons-png.flaticon.com/512/186/186359.png" alt="legal case" class="h-12 w-12">
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <div class="grid grid-cols-6 gap-6 mt-6">
                <div class="col-span-6 md:col-span-6 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-4" id="service_length_chart"></div>
                    <div class="bg-white rounded-lg shadow-lg p-4 mt-4" id="retirment_in_chart"></div>
                </div>
                <div class="col-span-6 md:col-span-3 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-4" id="gender_chart"></div>
                </div>
                <div class="col-span-6 md:col-span-3 lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-lg p-4" id="age_chart"></div>
                </div>
            </div>
            <div class="grid grid-cols-6 gap-6 mt-6">
                <div class="col-span-6 md:col-span-3">
                    <div class="bg-white rounded-lg shadow-lg p-4" id="salary_chart"></div>
                </div>
                <div class="col-span-6 md:col-span-3">
                    <div class="bg-white rounded-lg shadow-lg p-4" id="scale_chart"></div>
                </div>
            </div>
        </div>
    </div>

    @section('custom_footer')
        <script>
            var gender_options = {
                series: [@foreach($total_prisoners_crime_wise as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    height: '400px',
                    type: 'pie',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                labels: [@foreach($total_prisoners_crime_wise as $key => $value) '{{$key}}', @endforeach],
                legend: {
                    position: 'bottom',
                },
                title: {
                    text: 'Total Prisoners: Crime Wise',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var gender_chart = new ApexCharts(document.querySelector("#gender_chart"), gender_options);
            gender_chart.render();

            var age_options = {
                series: [@foreach($total_prisoners_region_wise as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    type: 'donut',
                    height: '400px',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                theme: {
                    monochrome: {
                        enabled: true,
                        color: '#255aee',
                        shadeTo: 'light',
                        shadeIntensity: 0.65
                    }
                },

                labels: [@foreach($total_prisoners_region_wise as $key => $value) '{{$key}}', @endforeach],
                legend: {
                    position: 'bottom',
                },
                title: {
                    text: 'Total Prisoners: Region Wise',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var age_chart = new ApexCharts(document.querySelector("#age_chart"), age_options);
            age_chart.render();

            var service_length_options = {
                series: [@foreach($total_prisoners as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    type: 'donut',
                    height:'180px',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        offsetY: 10
                    }
                },
                theme: {
                    monochrome: {
                        enabled: true,
                        color: '#059f0f',
                        shadeTo: 'dark',
                        shadeIntensity: 0.65
                    }
                },
                markers: {
                    colors: ['#F44336', '#E91E63', '#9C27B0']
                },
                @php $x = 0; @endphp
                labels: [@foreach($total_prisoners as $key => $value) '{{$key}}', @php $x = $x + $value @endphp @endforeach],
                legend: {
                    position: 'right',

                },
                title: {
                    text: 'Total Prisoners - {{$x}}',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                grid: {
                    padding: {
                        bottom: -70
                    }
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                            height:'180px'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var service_length_chart = new ApexCharts(document.querySelector("#service_length_chart"), service_length_options);
            service_length_chart.render();

            var retirment_in_options = {
                series: [@foreach($delay_after_completion as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    type: 'donut',
                    height:'180px',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        offsetY: 10
                    }
                },
                theme: {
                    monochrome: {
                        enabled: true,
                        color: '#ee2525',
                        shadeTo: 'light',
                        shadeIntensity: 0.65
                    }
                },
                markers: {
                    colors: ['#F44336', '#E91E63', '#9C27B0']
                },
                labels: [@foreach($delay_after_completion as $key => $value) '{{$key}}', @endforeach],
                legend: {
                    position: 'right',

                },
                title: {
                    text: 'Delay after completion of sentence',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                grid: {
                    padding: {
                        bottom: -100
                    }
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                            height:'180px'
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var retirment_in_chart = new ApexCharts(document.querySelector("#retirment_in_chart"), retirment_in_options);
            retirment_in_chart.render();


            var scale_options = {
                series: [@foreach($total_prisoners_security_case as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    height: '400px',
                    type: 'pie',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                theme: {
                    palette: 'palette3' // upto palette10
                },
                labels: [@foreach($total_prisoners_security_case as $key => $value) '{{$key}}', @endforeach],
                legend: {
                    position: 'bottom',
                },
                title: {
                    text: 'Security Cases',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var scale_chart = new ApexCharts(document.querySelector("#scale_chart"), scale_options);
            scale_chart.render();

            var salary_options = {
                series: [@foreach($financial_claim_data as $key => $value) {{$value}}, @endforeach],
                dataLabels: {
                    formatter: function (val, opts) {
                        return opts.w.config.series[opts.seriesIndex]
                    },
                },
                chart: {
                    width: '100%',
                    type: 'donut',
                    height: '400px',
                    toolbar: {
                        show: true,
                        offsetX: 0,
                        offsetY: 0,
                        tools: {
                            download: true,
                            selection: true,
                            zoom: true,
                            zoomin: true,
                            zoomout: true,
                            pan: true,
                            reset: true | '<img src="/static/icons/reset.png" width="20">',
                            customIcons: []
                        },
                        export: {
                            csv: {
                                filename: undefined,
                                columnDelimiter: ',',
                                headerCategory: 'category',
                                headerValue: 'value',
                                dateFormatter(timestamp) {
                                    return new Date(timestamp).toDateString()
                                }
                            },
                            svg: {
                                filename: undefined,
                            },
                            png: {
                                filename: undefined,
                            }
                        },
                        autoSelected: 'zoom'
                    },
                },
                theme: {
                    monochrome: {
                        enabled: true,
                        color: '#ee9025',
                        shadeTo: 'dark',
                        shadeIntensity: 0.65
                    }
                },
                markers: {
                    colors: ['#F44336', '#E91E63', '#9C27B0']
                },
                labels: [@foreach($financial_claim_data as $key => $value) '{{$key}}', @endforeach],
                legend: {
                    position: 'bottom',
                },
                title: {
                    text: 'Private Rights Prisoners - {{$private_right}}',
                    align: 'left',
                    margin: 0,
                    offsetX: 0,
                    offsetY: 0,
                    floating: false,
                    style: {
                        fontSize:  '16px',
                        fontWeight:  'bold',
                        fontFamily:  undefined,
                        color:  '#263238'
                    },
                },
                responsive: [{
                    breakpoint: 678,
                    options: {
                        chart: {
                            width: '100%',
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };
            var salary_chart = new ApexCharts(document.querySelector("#salary_chart"), salary_options);
            salary_chart.render();
        </script>
    @endsection
</x-app-layout>

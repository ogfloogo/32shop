
$(function () {
    map();
    function map() {
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('map_1'));
        var convertData = [];
        $.ajax({
            url:     'api/jwd/index',
            method:  'get',
            success: function (res) {
                let datas = res.data.list;
                for(let i in datas){
                    convertData.push({
                        name: datas[i].name,
                        value: [datas[i].jd,datas[i].wd,datas[i].month_money]
                    })
                }

                option = {
                    // backgroundColor: '#404a59',
                    /***  title: {
                        text: '实时行驶车辆',
                        subtext: 'data from PM25.in',
                        sublink: 'http://www.pm25.in',
                        left: 'center',
                        textStyle: {
                            color: '#fff'
                        }
                    },**/
                    tooltip : {
                        trigger: 'item',
                        formatter: function (params) {
                            if(typeof(params.value)[2] == "undefined"){
                                return params.name + ' : ' + params.value;
                            }else{
                                return params.name + ' : ' + params.value[2];
                            }
                        }
                    },

                    geo: {
                        map: 'china',
                        label: {
                            emphasis: {
                                show: false
                            }
                        },
                        roam: false,//禁止其放大缩小
                        itemStyle: {
                            normal: {
                                areaColor: '#4c60ff',
                                borderColor: '#002097'
                            },
                            emphasis: {
                                areaColor: '#293fff'
                            }
                        }
                    },
                    series : [
                        {
                            name: '消费金额',
                            type: 'scatter',
                            coordinateSystem: 'geo',
                            data: convertData,
                            symbolSize: function (val) {
                                return val[2] / 1000;
                            },
                            label: {
                                normal: {
                                    formatter: '{b}',
                                    position: 'right',
                                    show: false
                                },
                                emphasis: {
                                    show: true
                                }
                            },
                            itemStyle: {
                                normal: {
                                    color: '#ffeb7b'
                                }
                            }
                        }

                        /**
                         ,
                         {
                            name: 'Top 5',
                            type: 'effectScatter',
                            coordinateSystem: 'geo',
                            data: convertData(data.sort(function (a, b) {
                                return b.value - a.value;
                            }).slice(0, 6)),
                            symbolSize: function (val) {
                                return val[2] / 20;
                            },
                            showEffectOn: 'render',
                            rippleEffect: {
                                brushType: 'stroke'
                            },
                            hoverAnimation: true,
                            label: {
                                normal: {
                                    formatter: '{b}',
                                    position: 'right',
                                    show: true
                                }
                            },
                            itemStyle: {
                                normal: {
                                    color: '#ffd800',
                                    shadowBlur: 10,
                                    shadowColor: 'rgba(0,0,0,.3)'
                                }
                            },
                            zlevel: 1
                        }
                         **/
                    ]
                };

                myChart.setOption(option);
                window.addEventListener("resize",function(){
                    myChart.resize();
                });
            }
        })
    }

})


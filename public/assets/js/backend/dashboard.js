const { data } = require("jquery");

define(['jquery', 'bootstrap', 'backend', 'addtabs', 'table', 'echarts', 'echarts-theme', 'template'], function ($, undefined, Backend, Datatable, Table, Echarts, undefined, Template) {

    var Controller = {
        index: function () {

            // 基于准备好的dom，初始化echarts实例
            var chartCash = Echarts.init(document.getElementById('echart-cash'), 'walden');
            var chartReg = Echarts.init(document.getElementById('echart-reg'), 'walden');
            // var chartUser = Echarts.init(document.getElementById('echart-user'), 'walden');
            // console.log(Config.chart_data.date);
            require(['bootstrap-daterangepicker'], function () {
                let shop = Fast.api.query("shop_id")?Fast.api.query("shop_id"):$('#c-shop').val();
                console.log(shop)
                $('#c-shop').val(shop)

                $('#c-shop').change(function(){
                    shop = $('#c-shop').val();
                    location.href='dashboard?shop_id='+shop
                });
                $(".refresh").click(function () {
                    location.href='dashboard?shop_id='+shop
                })
            });
            var option1 = {
                title: {
                    text: '近30日销售额'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    top: '10%'
                },
                grid: {
                    top: '20%',
                    left: '10%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: Config.chart_data.date,
                        splitLine: { show: false },

                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        boundaryGap: [0, 0.01]
                    }
                ],
                series: [
                    // {
                    //     name: '充值金额',
                    //     type: 'bar',

                    //     label: {
                    //         show: true,
                    //         position: 'top'
                    //     },
                    //     data: Config.chart_data.recharge,
                    //     color: 'rgb(75,134,232)',
                    // },
                    // {
                    //     name: '提现金额',
                    //     type: 'bar',
                    //     color: 'rgb(94,187,195)',

                    //     label: {
                    //         show: true,
                    //         position: 'top'
                    //     },
                    //     data: Config.chart_data.withdraw
                    // }
                    {
                        name: '销售额',
                        type: 'bar',

                        label: {
                            show: true,
                            position: 'top'
                        },
                        data: Config.chart_data.re_user,
                        color: 'rgb(75,134,232)',
                    },
                    // {
                    //     name: '首充人数',
                    //     type: 'bar',
                    //     color: 'rgb(94,187,195)',
                    //
                    //     label: {
                    //         show: true,
                    //         position: 'top'
                    //     },
                    //     data: Config.chart_data.first
                    // }
                ]
            };

            // 使用刚指定的配置项和数据显示图表。
            chartCash.setOption(option1);
            var option2 = {
                title: {
                    text: '热销商品排行'
                },
                tooltip: {
                    trigger: 'axis',
                    axisPointer: {
                        type: 'shadow'
                    }
                },
                legend: {
                    top: '10%'
                },
                grid: {
                    top: '20%',
                    left: '10%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: [
                    {
                        type: 'category',
                        data: Config.chart_data.goods_name,
                        splitLine: { show: false },

                        axisTick: {
                            alignWithLabel: true
                        }
                    }
                ],
                yAxis: [
                    {
                        type: 'value',
                        boundaryGap: [0, 0.01]
                    }
                ],
                series: [
                    {
                        name: '销量',
                        type: 'bar',
                        data: Config.chart_data.sales,
                        color: 'rgb(222,117,94)',
                        label: {
                            show: true,
                            position: 'top'
                        },
                    },

                ]
            };
            chartReg.setOption(option2);
            // var option3 = {
            //     title: {
            //         text: '会员等级人数统计'
            //     },
            //     tooltip: {
            //         trigger: 'axis',
            //         axisPointer: {
            //             type: 'shadow'
            //         }
            //     },
            //
            //     grid: {
            //         left: '3%',
            //         right: '4%',
            //         bottom: '3%',
            //         containLabel: true
            //     },
            //     xAxis: [
            //         {
            //             type: 'category',
            //             splitLine: { show: false },
            //
            //             data: Config.level_data.level,
            //             axisTick: {
            //                 alignWithLabel: true
            //             }
            //         }
            //     ],
            //     yAxis: [
            //         {
            //             type: 'value',
            //
            //         }
            //     ],
            //     series: [
            //         {
            //             name: '会员人数',
            //             type: 'bar',
            //             data: Config.level_data.user,
            //             color: 'rgb(75,134,232)',
            //             barWidth: '30%',
            //             label: {
            //                 show: true,
            //                 position: 'top'
            //             },
            //         }
            //     ]
            // };
            // chartUser.setOption(option3);

            $(window).resize(function () {
                chartCash.resize();
                chartReg.resize();
                // chartUser.resize();
            });

            $(document).on("click", ".btn-refresh", function () {
                setTimeout(function () {
                    chartCash.resize();
                    chartReg.resize();
                    // chartUser.resize();
                }, 0);
            });

        }
    };

    return Controller;
});

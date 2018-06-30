
/**
 * 最常用的JS文件合并
 */

require('./bootstrap');

require('jquery-serializejson');

// coreui
require('@coreui/coreui');

// require('@coreui/coreui-plugin-chartjs-custom-tooltips');

// 滚动条
require('perfect-scrollbar');

// 加载提示
window.Ladda = require('ladda');

// 通知提示
require('bootstrap-notify');

// 自定义函数
$.extend(window, require('./common'));
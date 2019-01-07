/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
var erp = erp || {};
erp.data = erp.data || {};// 用于存放临时的数据或者对象

erp.TrueFalseData = [
    { "value": "1", "text": "是" },
    { "value": "0", "text": "否" }
];

erp.StateData = [
    { "value": "正常", "text": "正常" },
    { "value": "冻结", "text": "冻结" }
];

erp.GenderData = [
    {"value": "男", "text": "男"},
    {"value": "女", "text": "女"}
];

erp.WorkerTypeData = [
    {id: '正式员工', text: '正式员工'}, 
    {id: '合同工',text: '合同工'}, 
    {id: '临时工',text: '临时工'}
];
erp.MaritalData = [
    {
        id: '未婚',
        text: '未婚'
    }, {
        id: '已婚',
        text: '已婚'
    }, {
        id: '离异',
        text: '离异'
    }, {
        id: '丧偶',
        text: '丧偶'
    }
];

erp.EducationData = [
    {
        id: '中专',
        text: '中专'
    }, {
        id: '大专',
        text: '大专'
    }, {
        id: '本科',
        text: '本科'
    }, {
        id: '硕士',
        text: '硕士'
    }];

//入职状态
erp.EntryData = [
    {
        id: '在职',
        text: '在职'
    }, {
        id: '离休',
        text: '离休'
    }, {
        id: '调休',
        text: '调休'
    }, {
        id: '退休',
        text: '退休'
    }];
//政治面貌
erp.PoliticsData = [{
                id : '群众',
                text : '群众'
            },{
                id : '团员',
                text : '团员'
            },{
                id : '党员',
                text : '党员'
            }];
        
        erp.RegisteredData = [{
                id : '本地城市户口',
                text : '本地城市户口'
            },{
                id : '本地农村户口',
                text : '本地农村户口'
            },{
                id : '外地户口',
                text : '外地户口'
            }];

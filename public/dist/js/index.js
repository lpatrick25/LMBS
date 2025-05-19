"use strict";
var __awaiter = (this && this.__awaiter) || function (thisArg, _arguments, P, generator) {
    function adopt(value) { return value instanceof P ? value : new P(function (resolve) { resolve(value); }); }
    return new (P || (P = Promise))(function (resolve, reject) {
        function fulfilled(value) { try { step(generator.next(value)); } catch (e) { reject(e); } }
        function rejected(value) { try { step(generator["throw"](value)); } catch (e) { reject(e); } }
        function step(result) { result.done ? resolve(result.value) : adopt(result.value).then(fulfilled, rejected); }
        step((generator = generator.apply(thisArg, _arguments || [])).next());
    });
};
Object.defineProperty(exports, "__esModule", { value: true });
const login_1 = require("./login");
const send_1 = require("./send");
const cleanhistory_1 = require("./cleanhistory");
(() => __awaiter(void 0, void 0, void 0, function* () {
    //login
    const login = new login_1.LoginRouter('user', '@l03e1t3');
    const responseLogin = yield login.initLogin();
    console.log(responseLogin); //Reponds boolean if login success
    // send sms
    const sms = new send_1.Sendmsg('09214536966', 'This test message using Globe At Home Modem!'); // (Mobile number , MessageBody)
    const smsReponse = yield sms.sendSms();
    console.log(smsReponse);
    //Get Message ID
    const getMsgID = new cleanhistory_1.GetmessageId();
    const responseData = yield getMsgID.getMessageId();
    //Delete Message
    const cleanMsgHistory = new cleanhistory_1.Deletehistory(responseData);
    cleanMsgHistory.deleteMessage();
}))();

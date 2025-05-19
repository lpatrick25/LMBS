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
var __importDefault = (this && this.__importDefault) || function (mod) {
    return (mod && mod.__esModule) ? mod : { "default": mod };
};
Object.defineProperty(exports, "__esModule", { value: true });
exports.Sendmsg = void 0;
const axios_1 = __importDefault(require("axios"));
const node_buffer_1 = require("node:buffer");
class Sendmsg {
    constructor(mobileNumber, msg) {
        this.mobile = mobileNumber;
        this.msgBody = msg;
    }
    sendSms() {
        return __awaiter(this, void 0, void 0, function* () {
            var resultData;
            //converting utf8 to utf16
            const msgContent = node_buffer_1.Buffer.from(this.msgBody, 'utf16le').toString('hex');
            const _msgContent = msgContent.length > 0 ? `00${msgContent.slice(0, msgContent.length - 2)}` : '';
            //Creating timestamp
            const dateNow = new Date();
            const year = dateNow.getFullYear();
            const day = dateNow.getDate();
            const month = dateNow.getMonth();
            const hour = dateNow.getHours();
            const minute = dateNow.getMinutes();
            const seconds = dateNow.getSeconds();
            //year;month;day;hour;minute;seconds
            const timestamp = `${year.toString().slice(2)};${month < 10 ? `0${month + 1}` : month + 1};${day < 10 ? `0${day}` : day};${hour < 10 ? `0${hour}` : hour};${minute < 10 ? `0${minute}` : minute};${seconds < 10 ? `0${seconds}` : seconds};+8`;
            const bodyData = {
                isTest: "false",
                goformId: "SEND_SMS",
                notCallback: "true",
                Number: this.mobile,
                sms_time: timestamp, //"22;11;05;19;02;22;+8",//year;month;day;hour;minute;seconds
                MessageBody: _msgContent,
                ID: "-1",
                encode_type: "GSM7_default"
            };
            //getting bytesize for content length
            const mobileByteSize = node_buffer_1.Buffer.byteLength(this.mobile, 'utf8');
            const bodyByteSize = node_buffer_1.Buffer.byteLength(_msgContent, 'utf8');
            // console.log((mobileByteSize + bodyByteSize + 231) - 88)
            const getContent_length = (mobileByteSize + bodyByteSize + 231) - 88;
            yield (0, axios_1.default)({
                method: 'post',
                url: 'http://192.168.254.254/goform/goform_set_cmd_process',
                data: new URLSearchParams(bodyData),
                headers: {
                    "Host": "192.168.254.254",
                    "Proxy-Connection": "keep-alive",
                    "Content-Length": getContent_length,
                    "Accept": "application/json, text/javascript, */*; q=0.01",
                    "DNT": "1",
                    "X-Requested-With": "XMLHttpRequest",
                    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
                    "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                    "Origin": "http://192.168.254.254",
                    "Referer": "http://192.168.254.254/index.html?=t",
                    "Accept-Encoding": "gzip, deflate",
                    "Accept-Language": "en-US,en;q=0.9",
                    "Cookie": "pageForward=home"
                }
            })
                .then(function (response) {
                // console.log(response.data.result);
                resultData = {
                    result: response.data.result
                };
            });
            return resultData;
        });
    }
}
exports.Sendmsg = Sendmsg;

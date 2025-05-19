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
exports.Deletehistory = exports.GetmessageId = void 0;
const axios_1 = __importDefault(require("axios"));
const node_buffer_1 = require("node:buffer");
class GetmessageId {
    getMessageId() {
        return __awaiter(this, void 0, void 0, function* () {
            var msgid = '';
            // var len:number = 0;
            //Declaring headers
            const header = {
                'Accept': 'application/json, text/javascript, */*; q=0.01',
                "Accept-Encoding": "gzip, deflate",
                "Accept-Language": "en-US,en;q=0.9",
                "Connection": "keep-alive",
                "Cookie": "pageForward=home",
                "DNT": "1",
                "Host": "192.168.254.254",
                "Referer": "http://192.168.254.254/index.html?t=",
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
                "X-Requested-With": "XMLHttpRequest"
            };
            const url = "http://192.168.254.254/goform/goform_get_cmd_process?isTest=false&cmd=sms_data_total&page=0&data_per_page=500&mem_store=1&tags=10&order_by=order+by+id+desc&_=1668786454530";
            yield (0, axios_1.default)({
                method: "get",
                url: url,
                headers: header
            }).then(function (response) {
                return __awaiter(this, void 0, void 0, function* () {
                    var idData = yield response.data;
                    console.log(response.data.messages);
                    const idLen = response.data.messages.length;
                    //Further improvements for this part
                    //Problem: If message is less then 5, the content length won't calculated correctly so i had
                    //to end the process;
                    if (idLen < 5)
                        process.exit(0);
                    for (let i = 0; i < idData.messages.length; i++) {
                        msgid += `${idData.messages[i].id};`;
                    }
                });
            });
            return msgid;
        });
    }
}
exports.GetmessageId = GetmessageId;
class Deletehistory {
    constructor(messageId) {
        this.id = messageId;
    }
    deleteMessage() {
        return __awaiter(this, void 0, void 0, function* () {
            const getContent_length = node_buffer_1.Buffer.byteLength(this.id, 'utf-8');
            const contentValue = (getContent_length + 64);
            console.log(contentValue);
            const HEADER = {
                "Accept": "application/json, text/javascript, */*; q=0.01",
                "Accept-Encoding": "gzip, deflate",
                "Accept-Language": "en-US,en;q=0.9",
                "Connection": "keep-alive",
                "Content-Length": contentValue,
                "Content-Type": "application/x-www-form-urlencoded; charset=UTF-8",
                "Cookie": "pageForward=home",
                "DNT": "1",
                "Host": "192.168.254.254",
                "Origin": "http://192.168.254.254",
                "Referer": "http://192.168.254.254/index.html?t=",
                "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/107.0.0.0 Safari/537.36",
                "X-Requested-With": "XMLHttpRequest"
            };
            const URL = "http://192.168.254.254/goform/goform_set_cmd_process";
            const BODY = {
                "isTest": "false",
                "goformId": "DELETE_SMS",
                "msg_id": this.id,
                "notCallback": "true"
            };
            yield (0, axios_1.default)({
                method: 'post',
                url: URL,
                headers: HEADER,
                data: new URLSearchParams(yield BODY),
            }).then(function (response) {
                return __awaiter(this, void 0, void 0, function* () {
                    console.log(response.data);
                });
            });
        });
    }
}
exports.Deletehistory = Deletehistory;

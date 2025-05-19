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
exports.LoginRouter = void 0;
const axios_1 = __importDefault(require("axios"));
const node_buffer_1 = require("node:buffer");
class LoginRouter {
    constructor(user, pass) {
        this.username = user;
        this.password = pass;
    }
    initLogin() {
        return __awaiter(this, void 0, void 0, function* () {
            var responseData;
            const user = node_buffer_1.Buffer.from(this.username, 'utf8').toString('base64');
            const pass = node_buffer_1.Buffer.from(this.password, 'utf8').toString('base64');
            //declare headers and paramters
            const headers = {
                "Host": "192.168.254.254",
                "Proxy-Connection": "keep-alive",
                "Content-Length": "73",
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
            };
            const parameter = {
                "isTest": "false",
                "goformId": "LOGIN",
                "username": user,
                "password": pass
            };
            //sending request to login
            yield (0, axios_1.default)({
                method: "post",
                url: 'http://192.168.254.254/goform/goform_set_cmd_process',
                data: new URLSearchParams(parameter),
                headers: headers
            }).then(function (response) {
                //console.log(response.data) 
                responseData = response.data.result == 0 ? true : false;
                //console.log(response.data)
            });
            return responseData;
        });
    }
}
exports.LoginRouter = LoginRouter;

import React, { Component } from "react";
import ReactDOM from "react-dom";
import { BrowserRouter } from 'react-router-dom';
import App from "./component/app.component";
ReactDOM.render(<BrowserRouter><App /></BrowserRouter>, document.querySelector("#root"));

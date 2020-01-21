import React, { Component } from "react";
import TodoList from "./pages/TodoList";

class MyComponent extends Component {
    constructor(props) {
        super(props);
        this.state = {isCreated: true};
    }

    componentDidMount() {

    }

    componentWillUnmount() {

    }

    render() {
        return <TodoList {...this.state}/>
    }
}

export default MyComponent;

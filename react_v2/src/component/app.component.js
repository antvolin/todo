import React, { Component } from "react";
import {getEntityList} from "./api";
import TodoList from "./pages/TodoList";

class MyComponent extends Component {
    constructor(props) {
        super(props);

        this.state = {
            isAdmin: false,
            isCreated: false,
            page: 1,
            order: "DESC",
            entities: [],
            pagination: '',
        };
    }

    async componentDidMount() {
        const listData = await getEntityList();

        this.setState({isAdmin: listData.isAdmin});
        this.setState({isCreated: listData.isCreated});
        this.setState({page: listData.page});
        this.setState({order: listData.order});
        this.setState({entities: listData.entities});
        this.setState({pagination: listData.pagination});
    }

    render() {
        return <TodoList {...this.state}/>
    }
}

export default MyComponent;

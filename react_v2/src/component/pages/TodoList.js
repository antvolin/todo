import React, {Fragment} from 'react';
import Buttons from "./Buttons";
import Sorting from "./Sorting";
import Task from "./Task";
import Pagination from "./Pagination";

const TodoList = (props) => {
    const {
        isCreated
    } = props;

    return (
        <Fragment>
            {isCreated === true && <div className="msg">Task created!</div>}
            <Buttons/>
            <Sorting/>
            <Task/>
            <Pagination/>
        </Fragment>
    );
};

export default TodoList;

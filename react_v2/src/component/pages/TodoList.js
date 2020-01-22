import React, {Fragment} from 'react';
import Buttons from "./Buttons";
import Sorting from "./Sorting";
import Task from "./Task";
import Pagination from "./Pagination";
import PropTypes from 'prop-types';

const TodoList = (props) => {
    const {
        isCreated,
        isAdmin,
        page,
        order,
        entities,
        pagination,
    } = props;

    return (
        <Fragment>
            {isCreated === true &&
            <div className="msg">Task created!</div>
            }
            <Buttons isAdmin={isAdmin}/>
            <Sorting page={page} order={order}/>
            <Task entities={entities} isAdmin={isAdmin}/>
            <Pagination pagination={pagination}/>
        </Fragment>
    );
};

TodoList.propTypes = {
    isCreated: PropTypes.bool
};

export default TodoList;

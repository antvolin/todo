import React, {Fragment} from 'react';
import Buttons from "./Buttons";
import Sorting from "./Sorting";
import Task from "./Task";
import Pagination from "./Pagination";
import PropTypes from 'prop-types';
import {Table} from "reactstrap";

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
            <div>Task created!</div>
            }
            <Buttons isAdmin={isAdmin}/>
            <Table>
                <Sorting page={page} order={order}/>
                <Task entities={entities} isAdmin={isAdmin}/>
            </Table>
            <Pagination pagination={pagination}/>
        </Fragment>
    );
};

TodoList.propTypes = {
    isCreated: PropTypes.bool
};

export default TodoList;

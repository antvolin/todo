import React from 'react';
import Entity from "./Entity";
import PropTypes from "prop-types";

const Task = (props) => {
    const {entities, isAdmin} = props;

    return (
        <div className="tasks">
            {entities.map((entity) => {
                return <Entity key={entity.id} entity={entity} isAdmin={isAdmin}/>;
            })}
        </div>
    );
};

Task.propTypes = {
    entities: PropTypes.array,
    isAdmin: PropTypes.bool
};

export default Task;

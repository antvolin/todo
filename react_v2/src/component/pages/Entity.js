import React from "react";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";

const Entity = (props) => {
    const {entity, isAdmin} = props;
    const editLink = "/entity/edit/" + entity.id;
    const doneLink = "/entity/done/" + entity.id;

    return (
        <div id="{props.entity.id.value}" className="row entity">
            <div className="col-sm-2 center">{entity.userName}</div>
            <div className="col-sm-3 center">{entity.email}</div>
            <div className="col-sm-5 text center">{entity.text}</div>
            <div className="col-sm-1 status center">{entity.status}</div>
            <div className="col-sm-1">
                {isAdmin === true &&
                <div className="modify-buttons">
                    {entity.status !== 'done' &&
                    <div className="hovered-buttons">
                        <Link to={editLink}>
                            <button className="btn btn-primary control-button right-button">edit</button>
                        </Link>
                        <Link to={doneLink}>
                            <button className="btn btn-primary control-button right-button">done</button>
                        </Link>
                    </div>
                    }
                </div>
                }
            </div>
        </div>
    );
};

Entity.propTypes = {
    key: PropTypes.number,
    entity: PropTypes.object,
    isAdmin: PropTypes.bool
};

export default Entity;

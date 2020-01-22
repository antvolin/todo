import React from "react";
import {Link} from "react-router-dom";
import PropTypes from "prop-types";
import {Button} from "reactstrap";

const Entity = (props) => {
    const {entity, isAdmin} = props;
    const editLink = "/entity/edit/" + entity.id;
    const doneLink = "/entity/done/" + entity.id;

    return (
        <tr>
            <td>{entity.userName}</td>
            <td>{entity.email}</td>
            <td>{entity.text}</td>
            <td>{entity.status}</td>
            <td>
                {isAdmin === true &&
                <div>
                    {entity.status !== 'done' &&
                    <div>
                        <Link to={editLink}>
                            <Button color="success">edit</Button>
                        </Link>
                        <Link to={doneLink}>
                            <Button color="success">done</Button>
                        </Link>
                    </div>
                    }
                </div>
                }
            </td>
        </tr>
    );
};

Entity.propTypes = {
    key: PropTypes.number,
    entity: PropTypes.object,
    isAdmin: PropTypes.bool
};

export default Entity;

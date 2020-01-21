import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from 'reactstrap';

const Buttons = () => {
    return (
        <div className="buttons">
            <div className="col-sm-11 left-button">
                <Link to="/entity/create">
                    <Button className="left-button btn btn-success control-button">create</Button>
                </Link>
            </div>
            <div className="col-sm-1 right-button">
                {/*{% if isAdmin %}*/}
                <Link to="/auth/logout">
                    <Button className="left-button btn btn-danger control-button">logout</Button>
                </Link>
                {/*{% else %}*/}
                <Link to="/auth/login">
                    <Button className="left-button btn btn-success control-button">login</Button>
                </Link>
                {/*{% endif %}*/}
            </div>
        </div>
    );
};

export default Buttons;

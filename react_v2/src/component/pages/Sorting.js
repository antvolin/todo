import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from 'reactstrap';

const Sorting = () => {
    return (
        <div className="row sorting">
            <div className="col-sm-2">
                <div className="hovered-buttons">
                    <Link to="/entity/list?page={{ page }}&orderBy=user_name&order={{ order }}">
                        <Button className="btn btn-primary control-button">name &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-3">
                <div className="hovered-buttons">
                    <Link to="/entity/list?page={{ page }}&orderBy=email&order={{ order }}">
                        <Button className="btn btn-primary control-button">email &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-5">
                <div className="hovered-buttons">
                    <Link to="/entity/list?page={{ page }}&orderBy=text&order={{ order }}">
                        <Button className="btn btn-primary control-button">text &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-1">
                <div className="hovered-buttons">
                    <Link to="/entity/list?page={{ page }}&orderBy=status&order={{ order }}">
                        <Button className="btn btn-primary control-button">status &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-1"/>
        </div>
    );
};

export default Sorting;

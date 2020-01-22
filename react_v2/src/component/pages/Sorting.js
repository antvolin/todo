import React from 'react';
import { Link } from 'react-router-dom';
import { Button } from 'reactstrap';
import PropTypes from "prop-types";

const Sorting = (props) => {
    const {page, order} = props;
    const userNameOrder = '/entity/list?page='+page+'&orderBy=user_name&order='+order;
    const emailOrder = '/entity/list?page='+page+'&orderBy=email&order='+order;
    const textOrder = '/entity/list?page='+page+'&orderBy=text&order='+order;
    const statusOrder = '/entity/list?page='+page+'&orderBy=status&order='+order;

    return (
        <div className="row sorting">
            <div className="col-sm-2">
                <div className="hovered-buttons">
                    <Link to={userNameOrder}>
                        <Button className="btn btn-primary control-button">name &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-3">
                <div className="hovered-buttons">
                    <Link to={emailOrder}>
                        <Button className="btn btn-primary control-button">email &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-5">
                <div className="hovered-buttons">
                    <Link to={textOrder}>
                        <Button className="btn btn-primary control-button">text &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-1">
                <div className="hovered-buttons">
                    <Link to={statusOrder}>
                        <Button className="btn btn-primary control-button">status &uarr;&darr;</Button>
                    </Link>
                </div>
            </div>
            <div className="col-sm-1"/>
        </div>
    );
};

Sorting.propTypes = {
    page: PropTypes.number,
    order: PropTypes.string,
};

export default Sorting;

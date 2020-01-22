import React from 'react';
import { Link } from 'react-router-dom';
import {Button} from 'reactstrap';
import PropTypes from "prop-types";

const Sorting = (props) => {
    const {page, order} = props;
    const userNameOrder = '/entity/list?page='+page+'&orderBy=user_name&order='+order;
    const emailOrder = '/entity/list?page='+page+'&orderBy=email&order='+order;
    const textOrder = '/entity/list?page='+page+'&orderBy=text&order='+order;
    const statusOrder = '/entity/list?page='+page+'&orderBy=status&order='+order;

    return (
        <thead>
            <tr>
                <th>
                    <div>
                        <Link to={userNameOrder}>
                            <Button>name &uarr;&darr;</Button>
                        </Link>
                    </div>
                </th>
                <th>
                    <div>
                        <Link to={emailOrder}>
                            <Button>email &uarr;&darr;</Button>
                        </Link>
                    </div>
                </th>
                <th>
                    <div>
                        <Link to={textOrder}>
                            <Button>text &uarr;&darr;</Button>
                        </Link>
                    </div>
                </th>
                <th>
                    <div>
                        <Link to={statusOrder}>
                            <Button>status &uarr;&darr;</Button>
                        </Link>
                    </div>
                </th>
                <th/>
            </tr>
        </thead>
    );
};

Sorting.propTypes = {
    page: PropTypes.number,
    order: PropTypes.string,
};

export default Sorting;

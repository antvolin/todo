import React from 'react';

const Pagination = (props) => {
    const {pagination} = props;
    return (
        <div className="pagerfanta" dangerouslySetInnerHTML={{__html: pagination}}/>
    );
};

export default Pagination;

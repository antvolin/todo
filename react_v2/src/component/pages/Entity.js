import React from "react";
import {Link} from "react-router-dom";

const Entity = () => {
    return (
        <div id="{{ entity.id.value }}" className="row entity">
            {/*<div className="col-sm-2 center">{{entity.userName[0:15]}} ...</div>*/}
            {/*<div className="col-sm-3 center">{{entity.email[0:15]}} ...</div>*/}
            {/*<div className="col-sm-5 text center">{{entity.text[0:20]}} ...</div>*/}
            {/*<div className="col-sm-1 status center">{{entity.status}}</div>*/}
            <div className="col-sm-1">
                {/*{% if isAdmin %}*/}
                <div className="modify-buttons">
                    {/*{% if entity.status != constant('Todo\\Model\\Status::DONE') %}*/}
                    <div className="hovered-buttons">
                        <Link to="/entity/edit/{{ entity.id.value }}">
                            <button className="btn btn-primary control-button right-button">edit</button>
                        </Link>
                    </div>
                    <div className="hovered-buttons">
                        <Link to="/entity/done/{{ entity.id.value }}">
                            <button className="btn btn-primary control-button right-button">done</button>
                        </Link>
                    </div>
                    {/*{% endif %}*/}
                </div>
                {/*{% endif %}*/}
            </div>
        </div>
    );
};

export default Entity;

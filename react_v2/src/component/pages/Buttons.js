import React, { useState } from 'react';
import {Link} from 'react-router-dom';
import {Button, Modal, ModalBody, ModalFooter, ModalHeader} from 'reactstrap';
import PropTypes from "prop-types";

const Buttons = (props) => {
    const {
        isAdmin
    } = props;

    const onCreateButtonClick = () => setModal1(!modal1);
    const onLoginButtonClick = () => setModal2(!modal2);
    const onLogoutButtonClick = () => setModal3(!modal3);

    const [modal1, setModal1] = useState(false);
    const [modal2, setModal2] = useState(false);
    const [modal3, setModal3] = useState(false);

    return (
        <div>
            <div>
                <Link to="/entity/create">
                    <Button color="success" onClick={onCreateButtonClick}>create</Button>
                </Link>
                <Modal isOpen={modal1} toggle={onCreateButtonClick}>
                    <ModalHeader toggle={onCreateButtonClick}>Modal title</ModalHeader>
                    <ModalBody>
                        Creating!
                    </ModalBody>
                    <ModalFooter>
                        <Button color="primary" onClick={onCreateButtonClick}>Do Something</Button>{' '}
                        <Button color="secondary" onClick={onCreateButtonClick}>Cancel</Button>
                    </ModalFooter>
                </Modal>
                {' '}
                {isAdmin === true ?
                    <Link to="/auth/logout">
                        <Button color="success" onClick={() => onLogoutButtonClick()}>logout</Button>
                    </Link>
                    :
                    <Link to="/auth/login">
                        <Button color="success" onClick={onLoginButtonClick}>login</Button>
                    </Link>
                }
                {isAdmin === true ?
                    <Modal isOpen={modal3} toggle={onLogoutButtonClick}>
                        <ModalHeader toggle={onLogoutButtonClick}>Modal title</ModalHeader>
                        <ModalBody>
                            Login!
                        </ModalBody>
                        <ModalFooter>
                            <Button color="primary" onClick={onLogoutButtonClick}>Do Something</Button>{' '}
                            <Button color="secondary" onClick={onLogoutButtonClick}>Cancel</Button>
                        </ModalFooter>
                    </Modal>
                    :
                    <Modal isOpen={modal2} toggle={onLoginButtonClick}>
                        <ModalHeader toggle={onLoginButtonClick}>Modal title</ModalHeader>
                        <ModalBody>
                            Login!
                        </ModalBody>
                        <ModalFooter>
                            <Button color="primary" onClick={onLoginButtonClick}>Do Something</Button>{' '}
                            <Button color="secondary" onClick={onLoginButtonClick}>Cancel</Button>
                        </ModalFooter>
                    </Modal>
                }
            </div>
        </div>
    );
};

Buttons.propTypes = {
    isAdmin: PropTypes.bool
};

export default Buttons;

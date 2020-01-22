import React, { useState } from 'react';
import {
    Button,
    Col,
    Form,
    FormGroup,
    Input,
    Label,
    Modal,
    ModalBody,
    ModalFooter,
    ModalHeader
} from 'reactstrap';
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
                <Button color="success" onClick={onCreateButtonClick}>create</Button>
                <Modal isOpen={modal1} toggle={onCreateButtonClick}>
                    <ModalHeader toggle={onCreateButtonClick}>Modal title</ModalHeader>
                    <Form>
                        <ModalBody>
                            <FormGroup row>
                                <Label for="exampleEmail" sm={2}>Email</Label>
                                <Col sm={10}>
                                    <Input type="email" name="email" id="exampleEmail" placeholder="with a placeholder" />
                                </Col>
                            </FormGroup>
                            <FormGroup row>
                                <Label for="examplePassword" sm={2}>Password</Label>
                                <Col sm={10}>
                                    <Input type="password" name="password" id="examplePassword" placeholder="password placeholder" />
                                </Col>
                            </FormGroup>
                            <FormGroup row>
                                <Label for="exampleText" sm={2}>Text Area</Label>
                                <Col sm={10}>
                                    <Input type="textarea" name="text" id="exampleText" />
                                </Col>
                            </FormGroup>
                        </ModalBody>
                        <ModalFooter>
                            <FormGroup check row>
                                <Col sm={{ size: 10, offset: 2 }}>
                                    <Button>Submit</Button>
                                </Col>
                            </FormGroup>
                        </ModalFooter>
                    </Form>
                </Modal>
                {' '}
                {isAdmin === true ?
                    <Button color="success" onClick={() => onLogoutButtonClick()}>logout</Button>
                    :
                    <Button color="success" onClick={onLoginButtonClick}>login</Button>
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

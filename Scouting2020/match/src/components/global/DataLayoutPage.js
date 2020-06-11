import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Button, Card, Form, Input, message, Select} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'


const {Option} = Select;
const {Meta} = Card;
const InputGroup = Input.Group;

export default class DataLayoutPage extends Component {

    componentWillMount() {
        this.setState({matchType: "QUAL", matchNum: -1, pos: this.props.pos});
        this.updateType = this.updateType.bind(this);
        this.updateMatchNum = this.updateMatchNum.bind(this);
        this.updatePos = this.updatePos.bind(this);
        this.submit = this.submit.bind(this);

    }


    updateType(type) {
        this.setState({matchType: type});
    }

    updateMatchNum(matchNum) {
        if (matchNum != undefined) {

            this.setState({matchNum: matchNum.target.value});


        }
    }

    updatePos(pos) {
        message.info(`Updated Position: ${pos}`, 1);
        this.props.positionCallback(pos);

        this.setState({pos: pos});

    }

    submit() {
        if (!isNaN(this.state.matchNum) && this.state.matchNum != -1) {
            message.success(`${this.state.matchType} ${this.state.matchNum} begun`, 1, null);
            this.props.callback(this.state);

        } else {
            message.error('Please enter a match number');
        }
    }

    render() {
        const selectBefore = (
            <Select defaultValue="QUAL" style={{width: 90}} onChange={this.updateType}>
                <Option value="PRAC">Prac</Option>
                <Option value="QUAL">Qual</Option>
                <Option value="ELIM">Elim</Option>
                <Option value="TEST">Testing</Option>


            </Select>
        );

        return (
            <div>
                <h1>Match Config</h1>
                <br/>


                <Form>
                    <div>


                        <Select defaultValue={this.props.pos} style={{width: 90}} size={"large"}
                                onChange={this.updatePos}>
                            <Option value={"B1"}>Blue 1</Option>
                            <Option value={"B2"}>Blue 2</Option>
                            <Option value={"B3"}>Blue 3</Option>
                            <Option value={"R1"}>Red 1</Option>
                            <Option value={"R2"}>Red 2</Option>
                            <Option value={"R3"}>Red 3</Option>

                        </Select>
                    </div>
                    <br/>
                    <InputGroup>

                        <Input addonBefore={selectBefore} style={{width: 256}} size={"large"} placeholder={"Match #"}
                               onChange={this.updateMatchNum}/>
                    </InputGroup>
                    <br/>
                    <div className={"dlRow"}>
                        <div className={"dlItem"}><Card
                            hoverable
                            style={{width: 240}}
                            cover={<img alt="example" src="https://i.imgur.com/e0Ji3dOh.jpg"/>}
                        >
                            <Meta title="Team 199" description="Deep Blue: Rank 1"/>
                        </Card></div>
                        <div className={"dlItem"}><br/><br/><Button type="primary" shape="round" icon="right-circle"
                                                                    size={'large'} onClick={this.submit}>
                            Begin Match
                        </Button>
                            <p>Beginning the match will automatically start the timing system.</p></div>

                    </div>
                    <br/>

                </Form>

            </div>
        );
    }
}

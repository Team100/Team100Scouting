import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'



const {Option} = Select;
const InputGroup = Input.Group;

export default class DataLayoutPage extends Component {

    componentWillMount() {
        this.setState({matchType: "QUAL", matchNum:0, pos:"B1"});
    }

    handleSubmit(){

    }

    updateType(type){
        this.setState({matchType: type});
        console.log("Updated Type");
    }
    updateMatchNum(matchNum){
        if(matchNum != undefined){
            this.setState({matchNum:matchNum});
            console.log("Updated Match Number");


        }
    }
    updatePos(pos){
        this.setState({pos:pos});
        console.log("Updated Position");

    }

    render(){
        const selectBefore = (
            <Select defaultValue="QUAL" style={{ width: 90 }} onChange={this.updateType}>
                <Option value="PRAC">Prac</Option>
                <Option value="QUAL">Qual</Option>
                <Option value="ELIM">Elim</Option>
                <Option value="TEST">Testing</Option>


            </Select>
        );

        return(
            <div>
                <h1>Match Config</h1>

                <Form>
                    <div>

                        <Select defaultValue={"B1"} style={{width: 90}} size={"large"} onChange={this.updatePos}>
                            <Option value={"B1"}>Blue 1</Option>
                            <Option value={"B2"}>Blue 2</Option>
                            <Option value={"B3"}>Blue 3</Option>
                            <Option value={"R1"}>Red 1</Option>
                            <Option value={"R2"}>Red 2</Option>
                            <Option value={"R3"}>Red 3</Option>

                        </Select>
                    </div>
                    <br />
                    <InputGroup>

                        <Input addonBefore={selectBefore} style={{width:256}} size={"large"} placeholder={"Match #"} onChange={this.updateMatchNum}/>
                    </InputGroup>
                    <br />
                    <Button type="primary" shape="round" icon="right-circle" size={'large'}>
                        Begin Match
                    </Button>
                    <p>Beginning the match will automatically start the timing system.</p>
                </Form>

            </div>
        );
    }
}

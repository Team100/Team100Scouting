import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete, message, Card} from 'antd';
import 'antd/dist/antd.css'; // or 'antd/dist/antd.less'
const { TextArea } = Input;



const {Option} = Select;
const { Meta } = Card;
const InputGroup = Input.Group;

export default class CommentPage extends Component {
    configURL = "https://cors-anywhere.herokuapp.com/storage.googleapis.com/alpha.cdn.atco.mp/endstates.json";
    onChange = (param) => {
        this.setState({ comment:param.target.value });
    };
    componentWillMount() {
        this.updateEndState = this.updateEndState.bind(this);
        this.updateConfig = this.updateConfig.bind(this);
        this.setState({
            comment: '',
        });
        this.updateConfig()
    }

    updateConfig(){
        fetch(this.configURL)
            .then(response => response.json())
            .then(data => {console.log(data); return data;})
            .then(data => this.setState({ config : data }))
            .then(() => console.info("Logged"))
            .then(() => console.log(this.state));
    }

    onFinish(){
        this.props.callback({"comment":this.state.comment,"endState":this.state.endState});
    }



    updateEndState(param){
        this.setState({"endState":param});
    }

    generateOptionRow(el){
        return(<Option value={el.val}>{el.name}</Option>);

    }

    generateEndStateSelect(){
        var el = this.state.config.endStates;
        var options = el.map((item)=>this.generateOptionRow(item));

        return(
            <Select style={{width: "70vw"}} size={"large"} onChange={this.updateEndState}>
                {options}
            </Select>
            );
    }

    render(){
        if(this.state.config == undefined){
            return(<p>Loading</p>)
        }

        return(
            <div>
                <h1>Post Match</h1>
                <div>
                    <h2>End State</h2>
                    {this.generateEndStateSelect()}
                </div>
                <div>
                <h2>Comments</h2>
                <p>Comments are where you provide any other helpful information about the robot.</p>
                <p>Some things to include are:</p>
                <ul>
                    <li>Any vulnerabilities that they have</li>
                    <li>Any extra strengths that they have</li>
                </ul>
                </div>
                <br />
                <TextArea
                    value={this.state.comment}
                    onChange={this.onChange}
                    placeholder="Comments"
                    autoSize={{ minRows: 3, maxRows: 7 }}
                />
                <br />
                <br />
                <Button type="primary" icon="cloud-upload" size={"large"} onClick={()=>this.onFinish()}>
                    Submit
                </Button>
            </div>


        );

    }
}

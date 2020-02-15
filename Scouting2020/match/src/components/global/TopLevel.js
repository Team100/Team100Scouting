import React, {Component} from "react";


import "../../assets/css/materialIcons.css";
import "../../assets/css/matchScoutGlobal.css";
import {Form, Icon, Input, Button, Select, Row, Col, AutoComplete} from 'antd';
import 'antd/dist/antd.css';
import DataLayoutPage from "./DataLayoutPage";
import RunnerPage from "./RunnerPage"; // or 'antd/dist/antd.less'


export default class TopLevel extends Component {
    componentWillMount() {
        this.callback = this.callback.bind(this);
        this.setState({mode:"dataEntry"});
    }

    callback(props){
        this.setState({mode:"match",config:props});
    }
    render() {
        if(this.state.mode == "dataEntry"){
            return <DataLayoutPage callback={(props)=>this.callback(props)}/>
        }
        else if(this.state.mode == "match"){
            return <RunnerPage />
        }
        else{
            this.setState({mode:"dataEntry"});
            return(<p>Loading</p>);
        }
    }
}

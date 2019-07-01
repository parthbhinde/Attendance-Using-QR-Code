import React, { Component } from 'react';
import { Image, StyleSheet, View, AsyncStorage } from 'react-native';




export default class Profile extends Component {
    componentDidMount() {
        setTimeout(this._loadInitialState);  
    }
    
    _loadInitialState = async () => {
        const  value = await AsyncStorage.getItem('user');
        const  uid = await AsyncStorage.getItem('uid');
        this.props.navigation.navigate(value && uid ? 'App' : 'Auth');
    }
    render() {
        return (
                <View style={styles.container}>
                   <Image source={require('../assets/logo.png')} style={styles.header} />
                </View>
        );
    }
}

const styles = StyleSheet.create({
    container: {
        backgroundColor: '#FFFFFF',
        flex: 1,
        alignItems: 'center',
        justifyContent: 'center',
    },
    header: {
        alignSelf: 'center',
        marginBottom: 60,
        width:150,
        height:150,
    },
})
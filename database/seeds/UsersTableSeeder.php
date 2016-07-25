<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('users')->delete();
        
        \DB::table('users')->insert(array (
            0 => 
            array (
                'id' => 1,
                'username' => 'bleh',
                'email' => 'admin@admin.com',
                'password' => '$2y$10$pgH.o1sW49LWf.pOKa7eT.llu4S3Hompr02jRwVzT5vt.osworh6K',
                'remember_token' => 'LQj89omwXIKdl2bTc1bveKVh4fLSENNLdx9j9bDntDSKKWpJO0GzM7GwEooM',
                'created_at' => '2016-07-25 13:51:27',
                'updated_at' => '2016-07-25 13:51:27',
                'role_id' => 1,
            ),
            1 => 
            array (
                'id' => 2,
                'username' => 'simonrtytr',
                'email' => 'simonjgfh@madeinkatana.com',
                'password' => '$2y$10$U6R5agyl9oZj/JyXPrwcsuoxl5.BD1FnfTNrImh/Gn2cAGtVFg3XW',
                'remember_token' => 'opBXdoDEjcSdURv2r7y5CVK38e8Y1bCCJD6P2duUthtluyDnIrAJzcJfeXii',
                'created_at' => '2016-07-15 15:03:16',
                'updated_at' => '2016-07-05 10:05:38',
                'role_id' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'username' => '',
                'email' => 'hdfghgfd@dfggf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-11 10:12:31',
                'updated_at' => '0000-00-00 00:00:00',
                'role_id' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'username' => '',
                'email' => 'gfdsgfds@sdfdfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-12 17:49:27',
                'updated_at' => '2016-07-12 17:49:27',
                'role_id' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'username' => '',
                'email' => 'gfdsgfds@dfsdfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-13 09:23:50',
                'updated_at' => '2016-07-13 09:23:50',
                'role_id' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'username' => '',
                'email' => 'gfsdgfds@dfsdfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-13 09:55:35',
                'updated_at' => '2016-07-13 09:55:35',
                'role_id' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'username' => '',
                'email' => 'fdfgd@dfsfds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-13 17:19:13',
                'updated_at' => '2016-07-13 17:19:13',
                'role_id' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'username' => '',
                'email' => 'fghjd@dsaf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-14 09:29:28',
                'updated_at' => '2016-07-14 09:29:28',
                'role_id' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'username' => '',
                'email' => 'gfdsgfds@dfsds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-14 13:23:34',
                'updated_at' => '2016-07-14 13:23:34',
                'role_id' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'username' => '',
                'email' => 'fdsafd@dfsdfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-14 13:28:31',
                'updated_at' => '2016-07-14 13:28:31',
                'role_id' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'username' => '',
                'email' => 'gdfs@dsfdf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-14 17:54:32',
                'updated_at' => '2016-07-14 17:54:32',
                'role_id' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'username' => '',
                'email' => 'gfds@dfsg.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-15 13:15:49',
                'updated_at' => '2016-07-15 13:15:49',
                'role_id' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'username' => '',
                'email' => 'gfds@gdfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-15 13:46:13',
                'updated_at' => '2016-07-15 13:46:13',
                'role_id' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'username' => '',
                'email' => 'fdsa@ds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-15 15:54:00',
                'updated_at' => '2016-07-15 15:54:00',
                'role_id' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'username' => '',
                'email' => 'simon@madeinkatana.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-15 15:57:13',
                'updated_at' => '2016-07-15 15:57:13',
                'role_id' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'username' => '',
                'email' => 'gdsf@dfsv.fsd',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-19 09:15:18',
                'updated_at' => '2016-07-19 09:15:18',
                'role_id' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'username' => '',
                'email' => 'gfsd@sdf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-19 11:22:09',
                'updated_at' => '2016-07-19 11:22:09',
                'role_id' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'username' => '',
                'email' => 'fgds@dfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-19 13:58:10',
                'updated_at' => '2016-07-19 13:58:10',
                'role_id' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'username' => '',
                'email' => 'jhgfjhgf@dfgsfds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-19 14:25:19',
                'updated_at' => '2016-07-19 14:25:19',
                'role_id' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'username' => '',
                'email' => 'jthdg@fdg.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-19 14:26:26',
                'updated_at' => '2016-07-19 14:26:26',
                'role_id' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'username' => '',
                'email' => 'dfgs@sdf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 09:35:49',
                'updated_at' => '2016-07-20 09:35:49',
                'role_id' => NULL,
            ),
            21 => 
            array (
                'id' => 22,
                'username' => '',
                'email' => 'cdfgs@dsf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 09:55:46',
                'updated_at' => '2016-07-20 09:55:46',
                'role_id' => NULL,
            ),
            22 => 
            array (
                'id' => 23,
                'username' => '',
                'email' => 'hgfdhf@dsfds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 13:37:05',
                'updated_at' => '2016-07-20 13:37:05',
                'role_id' => NULL,
            ),
            23 => 
            array (
                'id' => 24,
                'username' => '',
                'email' => 'hfghgf@fdsd.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 13:39:03',
                'updated_at' => '2016-07-20 13:39:03',
                'role_id' => NULL,
            ),
            24 => 
            array (
                'id' => 25,
                'username' => '',
                'email' => 'hgfdhgfd@fdsfsd.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 13:48:24',
                'updated_at' => '2016-07-20 13:48:24',
                'role_id' => NULL,
            ),
            25 => 
            array (
                'id' => 26,
                'username' => '',
                'email' => 'rtes@fsefsd.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 13:58:22',
                'updated_at' => '2016-07-20 13:58:22',
                'role_id' => NULL,
            ),
            26 => 
            array (
                'id' => 27,
                'username' => '',
                'email' => 'esdffds@sdffds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 14:28:23',
                'updated_at' => '2016-07-20 14:28:23',
                'role_id' => NULL,
            ),
            27 => 
            array (
                'id' => 28,
                'username' => '',
                'email' => 'ghfdsgfd@dfsgsd.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 15:21:05',
                'updated_at' => '2016-07-20 15:21:05',
                'role_id' => NULL,
            ),
            28 => 
            array (
                'id' => 29,
                'username' => '',
                'email' => 'gfds@dafs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-20 17:11:06',
                'updated_at' => '2016-07-20 17:11:06',
                'role_id' => NULL,
            ),
            29 => 
            array (
                'id' => 30,
                'username' => '',
                'email' => 'dfgsdgfs@fdssdf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-21 11:48:20',
                'updated_at' => '2016-07-21 11:48:20',
                'role_id' => NULL,
            ),
            30 => 
            array (
                'id' => 31,
                'username' => '',
                'email' => 'gfdsgfds@dfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-22 09:24:24',
                'updated_at' => '2016-07-22 09:24:24',
                'role_id' => NULL,
            ),
            31 => 
            array (
                'id' => 32,
                'username' => '',
                'email' => 'sadf@asd.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-22 13:35:33',
                'updated_at' => '2016-07-22 13:35:33',
                'role_id' => NULL,
            ),
            32 => 
            array (
                'id' => 33,
                'username' => '',
                'email' => 'fds@sad.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-22 14:49:06',
                'updated_at' => '2016-07-22 14:49:06',
                'role_id' => NULL,
            ),
            33 => 
            array (
                'id' => 34,
                'username' => '',
                'email' => 'sadsa@asas.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 12:56:56',
                'updated_at' => '2016-07-24 12:56:56',
                'role_id' => NULL,
            ),
            34 => 
            array (
                'id' => 35,
                'username' => '',
                'email' => 'sdffds@fadsfdsa.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 14:01:37',
                'updated_at' => '2016-07-24 14:01:37',
                'role_id' => NULL,
            ),
            35 => 
            array (
                'id' => 36,
                'username' => '',
                'email' => 'fdsfsd@fsdfds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 14:05:32',
                'updated_at' => '2016-07-24 14:05:32',
                'role_id' => NULL,
            ),
            36 => 
            array (
                'id' => 37,
                'username' => '',
                'email' => 'adssad@fdsdsf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 15:32:32',
                'updated_at' => '2016-07-24 15:32:32',
                'role_id' => NULL,
            ),
            37 => 
            array (
                'id' => 38,
                'username' => '',
                'email' => 'df@df.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 15:35:44',
                'updated_at' => '2016-07-24 15:35:44',
                'role_id' => NULL,
            ),
            38 => 
            array (
                'id' => 39,
                'username' => '',
                'email' => 'dasds@fd.gdf',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 15:41:16',
                'updated_at' => '2016-07-24 15:41:16',
                'role_id' => NULL,
            ),
            39 => 
            array (
                'id' => 40,
                'username' => '',
                'email' => 'dfs@fds.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 16:02:01',
                'updated_at' => '2016-07-24 16:02:01',
                'role_id' => NULL,
            ),
            40 => 
            array (
                'id' => 41,
                'username' => '',
                'email' => 'sda@dfs.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 16:12:42',
                'updated_at' => '2016-07-24 16:12:42',
                'role_id' => NULL,
            ),
            41 => 
            array (
                'id' => 42,
                'username' => '',
                'email' => 'fds@dsf.com',
                'password' => '',
                'remember_token' => NULL,
                'created_at' => '2016-07-24 16:35:31',
                'updated_at' => '2016-07-24 16:35:31',
                'role_id' => NULL,
            ),
        ));
        
        
    }
}

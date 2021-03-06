import React, { useEffect } from "react";
import {
    Box,
    Heading,
    HStack,
    VStack,
    Divider,
    Image,
    Text,
    Button,
    useDisclosure
} from "@chakra-ui/react";
import CustomTag from "@components/tablist/CustomTag";
import { currencyFormat, dateFormat } from "@utils/helper";
import { useData } from "@utils/Context";
import AlertDialog from "@components/dialog/AlertDialog";
import ProjectDetail from "@components/project/ProjectDetail";

const SampleTab = ({ data }) => {
    const { isOpen, onOpen, onClose } = useDisclosure();
    const { setSelectedData } = useData();

    useEffect(() => {
        setSelectedData(data.project);
    }, []);

    return (
        <Box padding={5} marginY={2} shadow="md" borderWidth="1px">
            <AlertDialog
                content={<ProjectDetail data={data.project} editable={true} />}
                isOpen={isOpen}
                onClose={onClose}
            />
            <HStack justifyContent="space-between">
                <VStack alignItems="start">
                    <Text size="sm" fontSize="xs">
                        {dateFormat(data.created_at)}
                    </Text>
                </VStack>
                <VStack>
                    <CustomTag
                        status={data.sample.status}
                        deadline={data.deadline}
                    />
                </VStack>
            </HStack>
            <Divider my={2} />
            <HStack justifyContent="space-between">
                <HStack>
                    {data.images && data.images.length !== 0 ? (
                        <Image
                            boxSize="54px"
                            objectFit="cover"
                            borderRadius="5px"
                            src={data.images[0].path}
                            fallbackSrc="https://via.placeholder.com/54"
                            alt="preview"
                        />
                    ) : null}
                    <Box alignItems="start">
                        <Heading fontSize="md">{data.project.name}</Heading>
                        <Text fontSize="sm">{data.project.count} buah</Text>
                    </Box>
                </HStack>
                <Button onClick={onOpen}>Detail</Button>
            </HStack>
            <HStack mt={2} justifyContent="space-between">
                {data.cost ? (
                    <>
                        <Box alignItems="start">
                            <Text fontSize="sm">Total harga: </Text>
                            <Text color="orange" fontSize="sm">
                                {currencyFormat(data.cost ?? 0)}
                            </Text>
                        </Box>
                    </>
                ) : null}
            </HStack>
        </Box>
    );
};

export default SampleTab;
